<?php
namespace Nestor\Gateways;

use Exception;

use DB;
use Log;

use Nestor\Repositories\TestCaseRepository;
use Nestor\Repositories\TestCaseStepRepository;
use Nestor\Repositories\LabelRepository;
use Nestor\Repositories\NavigationTreeRepository;
use Nestor\Util\LabelsUtil;
use Nestor\Model\Nodes;

class TestCaseGateway 
{

	protected $testCaseRepository;
	protected $testCaseStepRepository;
	protected $labelRepository;
	protected $nodeRepository;

	public function __construct(
		TestCaseRepository $testCaseRepository,
		TestCaseStepRepository $testCaseStepRepository,
		LabelRepository $labelRepository,
		NavigationTreeRepository $nodeRepository)
	{
		$this->testCaseRepository = $testCaseRepository;
		$this->testCaseStepRepository = $testCaseStepRepository;
		$this->labelRepository = $labelRepository;
		$this->nodeRepository = $nodeRepository;
	}

	public function findTestCase($id)
	{
		return $this->testCaseRepository
			->findTestCase($id);
	}

	public function createTestCase($projectId, $testSuiteId, $executionTypeId, $name,
		$description, $prerequisite, $stepOrders, $stepDescriptions, $stepExpectedResults,
		$stepExecutionStatuses, $labels, $ancestor)
	{
		if (!$labels || is_null($labels) || !is_array($labels))
			$labels = array();

		// we retrieve all the labels already created for the current project
		$projectLabels = $this->labelRepository->findByProject($projectId);

		DB::beginTransaction();
		$testCase = NULL;
		try {
			Log::debug('Creating test case...');
			if (!$this->testCaseRepository->isNameAvailable(0, $testSuiteId, $name))	{
				throw new Exception('Name already taken. Please choose another name.');
			}
			list($testCase, $testCaseVersion) = $this->testCaseRepository->createNewTestCase(array(
				'project_id' => $projectId, 
				'test_suite_id' => $testSuiteId
			), array(
				'version' => 1,
				'execution_type_id' => $executionTypeId,
				'name' => $name, 
				'prerequisite' => $prerequisite,
				'description' => $description
			));

			// Labels
			{
				Log::debug('Creating labels for test case...');
				// here we check which labels will be created - IOW the project doesn't contain this label
				// and get a list of new label names to create, and an array of existing labels DB objects
				list($newLabelsNames, $oldLabels) = LabelsUtil::splitNewLabels($projectLabels, $labels);

				foreach ($newLabelsNames as $newLabelName) {
					$label = $this->labelRepository->create(array(
						'project_id' => $projectId, 
						'name' => $newLabelName, 
						'color' => 'gray'
					));
					$oldLabels[] = $label;
					Log::debug(sprintf('New label %s with color %s created for project %s', $newLabelName, $label['color'], $label['project_id']));
				}
				$this->testCaseRepository->addLabels($testCaseVersion['id'], $oldLabels);
			}

			// Steps
			{
				if (!isset($stepOrders) || !is_array($stepOrders) || empty($stepOrders)) {
					Log::debug('Not creating any steps for test case...');
				} else {
					Log::debug('Creating steps for test case...');
					for($i = 0; $i < count($stepOrders); ++$i) {
						$stepOrder = $stepOrders[$i];
						$stepDescription = $stepDescriptions[$i];
						$stepExpectedResult = $stepExpectedResults[$i];
						$stepExecutionStatus = $stepExecutionStatuses[$i];

						/*list($testcaseStep, $testcaseStepVersion) = */
						$this->testCaseStepRepository->createNewVersion(array(
							'expected_result' => $stepExpectedResult, 
							'execution_status_id' => $stepExecutionStatus
						), array(
							'version' => 1,
							'order' => $stepOrder, 
							'description' => $stepDescription,
							'test_case_version_id' => $testCase['id'], 
							'expected_result' => $stepExpectedResult, 
							'execution_status_id' => $stepExecutionStatus
						));
					}
					Log::debug('Test steps created');
				}
			}

			Log::debug('Inserting test case into the navigation tree...');
			$node = $this->nodeRepository->create(
				$ancestor,
				Nodes::id(Nodes::TEST_CASE_TYPE, $testCaseVersion['id']),
				$testCase['id'],
				Nodes::TEST_CASE_TYPE,
				$testCaseVersion['name']
			);

			Log::info(sprintf('New node %s inserted into the navigation tree', $node['node_id']));
			DB::commit();
			return array($testCase, $testCaseVersion);
		} catch (Exception $e) {
			Log::error($e);
			DB::rollback();
			throw $e;
		}
	}

	public function updateTestCase($id, $projectId, $labels)
	{
		if (!$labels || is_null($labels) || !is_array($labels))
			$labels = array();

		DB::beingTransaction();

		// we retrieve all the labels already created for the current project
		$projectLabels = $this->labelRepository->findByProject($projectId);

		$oldVersion = $this->findTestCase($id);

		try {
			Log::debug('Updating test suite...');
			$this->testCaseRepository->update(
				$id,
				array(
					'name' => $name, 
					'description' => $description
				)
			);

			Log::info(sprintf('Node %s updated in the navigation tree', $node['node_id']));
			DB::commit();
			return $testCase;
		} catch (Exception $e) {
			DB::rollback();
			throw $e;
		}

		$currentProject = $this->getCurrentProject();
		$labels = $this->labels->all($currentProject->id)->get();
		$testcase = null;
		$testcaseVersion = null;
		$navigationTreeNode = null;
		Log::info('Updating test case...');
		$pdo = null;
		try {
			$pdo = DB::connection()->getPdo();
			$pdo->beginTransaction();

			if (!$this->testcases->isNameAvailable($id, Input::get('test_suite_id'), Input::get('name')))
			{
				throw new Exception('Test case not updated: Name already taken.');
			}

			list($testcase, $testcaseVersion) = $this->testcases->update($id,
				Input::get('project_id'),
				Input::get('test_suite_id'),
				Input::get('execution_type_id'),
				Input::get('name'),
				Input::get('description'),
				Input::get('prerequisite'));

			if (!$testcaseVersion->isSaved()) 
			{
				throw new Exception('Test case version not updated: ' . $testcaseVersion->errors());
			}

			Log::info('Checking if there are test case steps...');
			$existingSteps = $testcaseVersion->steps->all();

			// update test case steps
			$stepIds = Input::get('step_id');
			$stepOrders = Input::get('step_order');
			$stepDescriptions = Input::get('step_description');
			$stepExpectedResults = Input::get('step_expected_result');
			$stepExecutionStatuses = Input::get('step_execution_status');
			if (isset($stepOrders) && is_array($stepOrders)) 
			{
				Log::info('Updating test case steps...');
				for($i = 0; $i < count($stepOrders); ++$i)
				{
					$stepId = $stepIds[$i];
					$stepOrder = $stepOrders[$i];
					$stepDescription = $stepDescriptions[$i];
					$stepExpectedResult = $stepExpectedResults[$i];
					$stepExecutionStatus = $stepExecutionStatuses[$i];

					if (strcmp($stepId, "-1") !== 0)
					{
						Log::debug(sprintf('Updating test case step %d', $stepId));
						list($testcaseStep, $testcaseStepVersion) = $this->testcaseSteps->update($stepId, $testcaseVersion->id, $stepOrder, $stepDescription, $stepExpectedResult, $stepExecutionStatus);
					}
					else
					{
						Log::debug('Creating new test case step');
						list($testcaseStep, $testcaseStepVersion) = $this->testcaseSteps->create($testcaseVersion->id, $stepOrder, $stepDescription, $stepExpectedResult, $stepExecutionStatus);
					}
					if (!$testcaseStepVersion->isValid() || !$testcaseStepVersion->isSaved())
					{
						Log::warning('Failed to save a test case step version. Rolling back.');
						throw new Exception('Failed to persist a test case step version. Check your input parameters.');
					}
				}
			}

			if (empty($stepIds))
			{
				foreach ($existingSteps as $existingStep)
				{
					Log::info("Deleting test case step: " . $existingStep->id);
					$this->testcaseSteps->delete($existingStep->id);
				}
			} 
			else 
			{
				top: foreach ($existingSteps as $existingStep) 
				{
					foreach ($stepIds as $stepId)
					{
						if ($stepId == $existingStep->id)
						{
							continue 2;
						}
					}
					Log::info("Deleting test case step: " . $existingStep->id);
					$this->testcaseSteps->delete($existingStep->id);
				}
			}

			$existingLabels = $testcaseVersion->labels()->get();
			$theLabels = Input::get('labels');
			if (isset($theLabels) && is_array($theLabels))
			{
				for($i = 0; $i < count($theLabels); ++$i)
				{
					$labelName = $theLabels[$i];
					$found = FALSE;
					foreach ($labels as $projectLabel)
					{
						if ($labelName == $projectLabel->name)
						{
							$found = TRUE;
							$label = $projectLabel;
							break;
						}
					}
					if (!$found)
					{
						$label = $this->labels->create($currentProject->id, $labelName, 'gray');
					}
					$found = FALSE;
					foreach ($existingLabels as $existingLabel)
					{
						if ($labelName == $existingLabel->name)
						{
							$found = TRUE;
							$label = $existingLabel;
							break;
						}
					}
					if (!$found)
					{
						$testcaseVersion->labels()->attach($label->id);
						Log::debug(sprintf('Label %s added to test case version id %d', $labelName, $testcaseVersion->id));
					}
				}
			}
			if (empty($theLabels))
			{
				foreach ($existingLabels as $existingLabel)
				{
					$testcaseVersion->labels()->detach($existingLabel->id);
				}
			}
			else
			{
				top2: foreach ($existingLabels as $existingLabel) 
				{
					foreach ($theLabels as $theLabel)
					{
						if ($theLabel == $existingLabel->name)
						{
							continue 2;
						}
					}
					Log::info("Deleting label " . $existingLabel->id);
					$testcaseVersion->labels()->dettach($existingLabel->id);
				}
			}

			$navigationTreeNode = $this->nodes->updateDisplayNameByDescendant(
				'3-'.$testcase->id,
				$testcaseVersion->name);
			Log::debug('Committing transaction');
			$pdo->commit();	
		} catch (\Exception $e) {
			Log::error($e);
			if (!is_null($pdo)) 
			{
				Log::warning('Rolling back transaction');
				$pdo->rollBack();
			}
			return Redirect::to(sprintf('/testcases/%d/edit', $id))
				->withInput()
				->with('error', $e->getMessage());
		}
		if (!is_null($testcase) && !is_null($testcaseVersion))
		{
			Log::info(sprintf('Test case %d updated.', $testcase->id));
			return Redirect::to(sprintf('/specification/nodes/%s-%s', 3, $testcase->id))
				->with('success', 'Test case updated');
		} else {
			return Redirect::to(sprintf('/testcases/%d/edit', $id))
				->withInput();
		}
	}

}
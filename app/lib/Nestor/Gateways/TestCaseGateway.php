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

	public function updateTestCase($id, $projectId, $testSuiteId, $executionTypeId, $name,
		$description, $prerequisite, $stepOrders, $stepDescriptions, $stepExpectedResults,
		$stepExecutionStatuses, $labels, $ancestor)
	{
		if (!$labels || is_null($labels) || !is_array($labels))
			$labels = array();

		DB::beginTransaction();

		// we retrieve all the labels already created for the current project
		$projectLabels = $this->labelRepository->findByProject($projectId);

		$oldVersion = $this->findTestCase($id);

		$testCase = NULL;
		try {
			Log::debug('Updating test case...');
			if (!$this->testCaseRepository->isNameAvailable(0, $testSuiteId, $name))	{
				throw new Exception('Name already taken. Please choose another name.');
			}
			list($testCase, $testCaseVersion) = $this->testCaseRepository->createNewVersion(
				$id,
				array(
				'version' => (1 + $oldVersion['version']['version']),
				'execution_type_id' => $executionTypeId,
				'name' => $name, 
				'prerequisite' => $prerequisite,
				'description' => $description
				)
			);

			// Labels
			{
				if (!isset($labels) || !is_array($labels) || empty($labels)) {
					Log::debug('Not creating any labels for test case...');
				} else {
					Log::debug('Creating labels for new test case version...');
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

			Log::debug('Updating test case in the navigation tree...');
			$node = $this->nodeRepository->update(
				Nodes::id(Nodes::TEST_CASE_TYPE, $id),
				Nodes::id(Nodes::TEST_CASE_TYPE, $id),
				$id,
				Nodes::TEST_CASE_TYPE,
				$name
			);

			Log::info(sprintf('Node %s updated in the navigation tree', $node['node_id']));
			DB::commit();
			return array($testCase, $testCaseVersion);
		} catch (Exception $e) {
			DB::rollback();
			throw $e;
		}
	}

}
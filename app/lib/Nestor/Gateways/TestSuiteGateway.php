<?php
namespace Nestor\Gateways;

use Exception;

use DB;
use Log;

use Nestor\Repositories\TestSuiteRepository;
use Nestor\Repositories\LabelRepository;
use Nestor\Repositories\NavigationTreeRepository;
use Nestor\Util\LabelsUtil;
use Nestor\Model\Nodes;

class TestSuiteGateway 
{

	protected $testSuiteRepository;
	protected $labelRepository;
	protected $nodeRepository;

	public function __construct(
		TestSuiteRepository $testSuiteRepository,
		LabelRepository $labelRepository,
		NavigationTreeRepository $nodeRepository)
	{
		$this->testSuiteRepository = $testSuiteRepository;
		$this->labelRepository = $labelRepository;
		$this->nodeRepository = $nodeRepository;
	}

	public function findByProject($projectId) 
	{
		return $this->testSuiteRepository->findByProject($projectId);
	}

	public function findTestSuite($testSuiteId) 
	{
		$testSuite = $this->testSuiteRepository->findWith($testSuiteId, array('labels'));
		return $testSuite;
	}

	public function createTestSuite($projectId, $name, $description, $labels, $ancestor)
	{
		if (!$labels || is_null($labels) || !is_array($labels))
			$labels = array();

		// we retrieve all the labels already created for the current project
		$projectLabels = $this->labelRepository->findByProject($projectId);

		DB::beginTransaction();
		$testSuite = NULL;
		try {
			Log::debug('Creating test suite...');
			$testSuite = $this->testSuiteRepository->create(array(
				'project_id' => $projectId, 
				'name' => $name, 
				'description' => $description
			));

			Log::debug('Creating labels for test suite...');
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

			$this->testSuiteRepository->addLabels($testSuite['id'], $oldLabels);

			Log::debug('Inserting test suite into the navigation tree...');
			$node = $this->nodeRepository->create(
				$ancestor,
				Nodes::id(Nodes::TEST_SUITE_TYPE, $testSuite['id']),
				$testSuite['id'],
				Nodes::TEST_SUITE_TYPE,
				$testSuite['name']
			);

			Log::info(sprintf('New node %s inserted into the navigation tree', $node['node_id']));
			DB::commit();
			return $testSuite;
		} catch (Exception $e) {
			Log::error($e);
			DB::rollback();
			throw $e;
		}
	}

}
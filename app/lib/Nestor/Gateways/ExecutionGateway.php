<?php 
namespace Nestor\Gateways;

use Exception;

use DB;
use Log;
use Session;

use Nestor\Repositories\ExecutionRepository;
use Nestor\Repositories\TestRunRepository;

class ExecutionGateway
{
	protected $executionRepository;
	protected $testRunRepository;

	public function __construct(ExecutionRepository $executionRepository,
		TestRunRepository $testRunRepository)
	{
		$this->executionRepository = $executionRepository;
		$this->testRunRepository = $testRunRepository;
	}

	public function createExecution($testPlanId, $name, $description) 
	{
		if (!$this->testRunRepository->isNameAvailable(0, $testPlanId, $name))
		{
			Log::warning(sprintf('Duplicate Test Run name [%s] found in the same Test plan [%d]', 
				$name, $testPlanId));
			throw new Exception("This name has already been taken");
			// return Redirect::to('/execution/testruns/create?test_plan_id=' . Input::get('test_plan_id'))
			// 	->withInput()
			// 	->withErrors($messages);
		}

		Log::info('Creating test run...');

		$testRun = $this->testRunRepository->create(array(
			'test_plan_id' => $testPlanId, 
			'name' => $name, 
			'description' => $description)
		);
		return $testRun;
	}

	public function getExecutionsForTestCaseVersion($testRunId, $testCaseVersionId)
	{
		return $this->executionRepository->getExecutionsForTestCaseVersion($testRunId, $testCaseVersionId);
	}

	public function updateExecution($id, $testPlanId, $name, $description)
	{
		if (!$this->testRunRepository->isNameAvailable(0, $testPlanId, $name))
		{
			Log::warning(sprintf('Duplicate Test Run name [%s] found in the same Test plan [%d]', 
				$name, $testPlanId));
			throw new Exception("This name has already been taken");
		}

		Log::info('UPdating test run...');

		$testRun = $this->testRunRepository->update($id, array(
			'test_plan_id' => $testPlanId, 
			'name' => $name, 
			'description' => $description)
		);
		return $testRun;
	}
} 
<?php 
namespace Nestor\Gateways;

use Exception;

use DB;
use Log;
use Session;

use Nestor\Repositories\ExecutionRepository;
use Nestor\Repositories\TestRunRepository;
use Nestor\Repositories\TestCaseRepository;

class ExecutionGateway
{
	protected $executionRepository;
	protected $testRunRepository;

	public function __construct(ExecutionRepository $executionRepository,
		TestRunRepository $testRunRepository,
		TestCaseRepository $testCaseRepository)
	{
		$this->executionRepository = $executionRepository;
		$this->testRunRepository = $testRunRepository;
		$this->testCaseRepository = $testCaseRepository;
	}

	public function createExecution($testPlanId, $name, $description) 
	{
		if (!$this->testRunRepository->isNameAvailable(0, $testPlanId, $name))
		{
			Log::warning(sprintf('Duplicate Test Run name [%s] found in the same Test plan [%d]', 
				$name, $testPlanId));
			throw new Exception("This name has already been taken");
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

		Log::info('Updating test run...');

		$testRun = $this->testRunRepository->update($id, array(
			'test_plan_id' => $testPlanId, 
			'name' => $name, 
			'description' => $description)
		);
		return $testRun;
	}

	public function executeTestCase($testRunId, $testCaseId, $executionStatusId, $notes)
	{
		$testRun = $this->testRunRepository->find($testRunId);
		$testCase = $this->testCaseRepository->findTestCase($testCaseId);
		$testCaseVersion = $testCase['version'];
		$steps = $testCase['version']['steps'];

		Log::debug("Running initial verifications...");
		$stepResults = array();
		foreach ($_POST as $key => $value)
		{
			$matches = array();
			if (preg_match('^step_execution_status_id_(\d+)^', $key, $matches))
			{
				$stepResults[substr($key, strlen('step_execution_status_id_'))] = $value;
			}
		}
		if (count($stepResults) != count($steps))
		{
			// Never supposed to happen
			Log::warning('Internal error. Wrong number of test steps execution statuses.');
			throw new Exception('Internal error. Wrong number of test steps execution statuses.');
		}
		foreach ($stepResults as $key => $value) 
		{
			if ($value == ExecutionStatus::NOT_RUN) // FIXME use constants
			{
				Log::warning('Trying to set the test case step execution status back to Not Run');
				throw new Exception(sprintf('You cannot set step %d execution status to Not Run', $key));
			}
		}

		Log::debug('Retrieving test run');
		DB::beginTransaction();
		try 
		{
			Log::debug(sprintf('Creating a new execution for test case version %d with execution status %d', $testCaseVersion['id'], $executionStatusId));
			$execution = $this->executionRepository->create(array(
				'test_run_id' => $testRun['id'], 
				'test_case_version_id' => $testCaseVersion['id'], 
				'execution_status_id' => $executionStatusId, 
				'notes' => $notes));

			// save its steps execution statuses
			foreach ($stepResults as $key => $value) 
			{
				Log::debug(sprintf('Creating new step execution for execution %d', $execution->id));
				$stepExecution = $this->stepExecutions->create($execution->id, $key, $value);
				if (!$stepExecution->isValid() || !$stepExecution->isSaved())
				{
					Log::error(var_export($stepExecution->errors(), TRUE));
					throw new Exception(sprintf("Failed to save step %d with execution status %d", $key, $value));
				}
			}
			Log::debug('Committing transaction');
			DB::commit();
			return $execution;
		} catch (Exception $e)
		{
			Log::error($e);
			DB::rollback();
			throw $e;
		}
	}
} 
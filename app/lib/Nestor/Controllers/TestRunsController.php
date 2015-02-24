<?php
namespace Nestor\Controllers;

use BaseController;
use Input;
use Restable;
use Log;
use Db;

use Exception;

use Nestor\Gateways\TestRunGateway;
use Nestor\Gateways\ExecutionGateway;

class TestRunsController extends BaseController {

	protected $testRunGateway = NULL;
	protected $executionGateway = NULL;

	public function __construct(TestRunGateway $testRunGateway, ExecutionGateway $executionGateway)
	{
		$this->testRunGateway = $testRunGateway;
		$this->executionGateway = $executionGateway;
	}

	public function show($testRunId)
	{
		$testRun = $this->testRunGateway->findTestRun($testRunId);
		return Restable::single($testRun)->render();
	}

	public function getExecutionsForTestCaseVersion($testRunId, $testCaseVersionId)
	{
		$executions = $this->executionGateway->getExecutionsForTestCaseVersion($testRunId, $testCaseVersionId);
		return Restable::listing($executions)->render();
	}

	public function executeTestCase()
	{
		$testRunId = Input::get('test_run_id');
		$testCaseId = Input::get('test_case_id');
		$executionStatusId = Input::get('execution_status_id');
		$notes = Input::get('notes');
		$execution = $this->executionGateway->executeTestCase($testRunId, $testCaseId, $executionStatusId, $notes);
		return Restable::single($execution)->render();
	}

}
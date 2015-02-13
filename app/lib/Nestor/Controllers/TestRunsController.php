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
		return $testRun;
	}

	public function getExecutionsForTestCaseVersion($testRunId, $testCaseVersionId)
	{
		$executions = $this->executionGateway->getExecutionsForTestCaseVersion($testRunId, $testCaseVersionId);
		var_dump($executions);exit;
	}

}
<?php
namespace Nestor\Controllers;

use BaseController;
use Input;
use Restable;
use Log;
use Db;

use Exception;

use Nestor\Gateways\TestRunGateway;

class TestRunsController extends BaseController {

	public function __construct(TestRunGateway $testRunGateway)
	{
		$this->testRunGateway = $testRunGateway;
	}

	public function show($testRunId)
	{
		$testRun = $this->testRunGateway->findTestRun($testRunId);
		return $testRun;
	}

}
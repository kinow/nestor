<?php
namespace Nestor\Controllers;

use BaseController;
use Restable;

use Nestor\Gateways\TestSuiteGateway;

class TestSuitesController extends BaseController 
{

	public function __construct(TestSuiteGateway $testSuiteGateway)
	{
		$this->testSuiteGateway = $testSuiteGateway;
	}

	// used to retrieve the test suites in a project, used to auto-complete project forms
	public function getTestSuitesByProject($projectId) 
	{
		$testSuites = $this->testSuiteGateway->findByProject($projectId);
		return Restable::listing($testSuites)->render();
	}

}
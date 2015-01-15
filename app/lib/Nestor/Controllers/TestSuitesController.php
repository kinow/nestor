<?php
namespace Nestor\Controllers;

use Exception;

use BaseController;
use Restable;
use Input;

use Nestor\Gateways\TestSuiteGateway;
use Nestor\Util\ValidationException;

class TestSuitesController extends BaseController 
{

	protected $testSuiteGateway;

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

	public function show($id)
	{
		$testSuite = $this
			->testSuiteGateway
			->findTestSuite($id);
		return Restable::single($testSuite)->render();
	}

	public function store()
	{
		$testSuite = NULL;
		try {
			$testSuite = $this->testSuiteGateway->createTestSuite(
				Input::get('project_id'),
				Input::get('name'),
				Input::get('description'),
				Input::get('labels'),
				Input::get('ancestor')
			);
		} catch (ValidationException $ve) {
			return Restable::error($ve->getErrors())->render();
		} catch (Exception $e) {
			return Restable::bad($e->getMessage())->render();
		}
		return Restable::created($testSuite)->render();
	}

}
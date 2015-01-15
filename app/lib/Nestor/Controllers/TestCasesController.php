<?php
namespace Nestor\Controllers;

use Exception;

use BaseController;
use Restable;
use Input;

use Nestor\Gateways\TestCaseGateway;
use Nestor\Util\ValidationException;

class TestCasesController extends BaseController
{

	protected $testCaseGateway;

	public function __construct(TestCaseGateway $testCaseGateway)
	{
		$this->testCaseGateway = $testCaseGateway;
	}

	public function show($id)
	{
		$testCase = $this
			->testCaseGateway
			->findTestCase($id);
		return Restable::single($testCase)->render();
	}

	public function store()
	{
		$testCase = NULL;
		$testCaseVersion = NULL;
		try {
			list($testCase, $testCaseVersion) = $this->testCaseGateway->createTestCase(
				Input::get('project_id'),
				Input::get('test_suite_id'),
				Input::get('execution_type_id'),
				Input::get('name'),
				Input::get('description'),
				Input::get('prerequisite'),
				Input::get('step_order'),
				Input::get('step_description'),
				Input::get('step_expected_result'),
				Input::get('step_execution_status'),
				Input::get('labels'),
				Input::get('ancestor')
			);
			$testCase['version'] = $testCaseVersion;
		} catch (ValidationException $ve) {
			return Restable::error($ve->getErrors())->render();
		} catch (Exception $e) {
			return Restable::bad($e->getMessage())->render();
		}
		return Restable::created($testCase)->render();
	}

}
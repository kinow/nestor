<?php
namespace Nestor\Controllers;

use BaseController;
use Input;
use Log;

use Restable;

use Nestor\Gateways\TestPlanGateway;

class TestPlansController extends BaseController
{

	protected $testPlanGateway;

	public function __construct(TestPlanGateway $testPlanGateway)
	{
		$this->testPlanGateway = $testPlanGateway;
	}

	public function index()
	{
		$testPlans = $this
			->testPlanGateway
			->paginateTestPlansForProject(10, Input::get('project_id'));
		return Restable::listing($testPlans)->render();
	}

	public function indexForProject($projectId)
	{
		$testPlans = $this
			->testPlanGateway
			->paginateTestPlansForProject(10, $projectId);
		return Restable::listing($testPlans)->render();
	}

	public function store()
	{
		$testPlan = NULL;
		try {
			$testPlan = $this
				->testPlanGateway
				->createTestPlan(
					Input::get('project_id'),
					Input::get('name'),
					Input::get('description')
				);
		} catch (ValidationException $ve) {
			return Restable::error($ve->getErrors())->render();
		} catch (Exception $e) {
			return Restable::bad($e->getMessage())->render();
		}
		return Restable::created($testPlan)->render();
	}

	public function show($id)
	{
		$testPlan = $this
			->testPlanGateway
			->findTestPlan($id);
		return Restable::single($testPlan)->render();
	}

	public function update($id)
	{
		try {
			$project = $this
				->testPlanGateway
				->updateTestPlan($id, Input::get('name'), Input::get('description'));
		} catch (ValidationException $ve) {
			return Restable::error($ve->getErrors())->render();
		} catch (Exception $e) {
			DB::rollback();
			throw $e;
		}
		return Restable::updated($project)->render();
	}

	public function addTestCase($testPlanId, $testCaseVersionId)
	{
		return $this
			->testPlanGateway
			->attachTestCase($testPlanId, $testCaseVersionId);
	}

	public function removeTestCase($testPlanId, $testCaseVersionId)
	{
		return $this
			->testPlanGateway
			->detachTestCase($testPlanId, $testCaseVersionId);
	}

}
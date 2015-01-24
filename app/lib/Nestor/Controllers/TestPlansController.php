<?php
namespace Nestor\Controllers;

use BaseController;

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
			->paginateTestPlans(10);
		return Restable::listing($testPlans)->render();
	}

	public function indexForProject($projectId)
	{
		$testPlans = $this
			->testPlanGateway
			->paginateTestPlansForProject(10, $projectId);
		return Restable::listing($testPlans)->render();
	}

}
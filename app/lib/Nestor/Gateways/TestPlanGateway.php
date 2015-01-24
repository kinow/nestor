<?php
namespace Nestor\Gateways;

use Nestor\Repositories\TestPlanRepository;

class TestPlanGateway
{

	protected $testPlanRepository;

	public function __construct(TestPlanRepository $testPlanRepository)
	{
		$this->testPlanRepository = $testPlanRepository;
	}

	public function paginateTestPlans($perPage)
	{
		$testPlans = $this
			->testPlanRepository
			->paginateWith($perPage, array());
		return $testPlans;
	}

	public function paginateTestPlansForProject($perPage, $projectId)
	{
		$testPlans = $this
			->testPlanRepository
			->paginateTestPlansForProject($perPage, $projectId, array());
		return $testPlans;
	}

}
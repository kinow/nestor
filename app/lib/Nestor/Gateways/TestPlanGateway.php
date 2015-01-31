<?php
namespace Nestor\Gateways;

use DB;
use Log;

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
			->paginateTestPlansForProjectWith($perPage, $projectId, array('testCases'));
		return $testPlans;
	}

	public function createTestPlan($projectId, $name, $description)
	{
		DB::beginTransaction();
		$testPlan = NULL;
		try {
			Log::debug('Creating test plan...');
			$testPlan = $this->testPlanRepository->create(array(
				'project_id' => $projectId,
				'name' => $name,
				'description' => $description
			));
			Log::info(sprintf("Test Plan %s created", $testPlan['name']));

			DB::commit();
			return $testPlan;
		} catch (Exception $e) {
			Log::error($e);
			DB::rollback();
			throw $e;
		}
	}

	public function findTestPlan($testPlanId)
	{
		$testPlan = $this->testPlanRepository->findWith($testPlanId, array('testCases'));
		return $testPlan;
	}

	public function updateTestPlan($id, $name, $description)
	{
		Log::debug('Updating test plan...');
		$testPlan = $this->testPlanRepository->update(
			$id,
			array(
				'name' => $name,
				'description' => $description
			)
		);
		return $testPlan;
	}

	public function attachTestCase($testPlanId, $testCaseVersionId)
	{
		return $this->testPlanRepository->attachTestCase($testPlanId, $testCaseVersionId);
	}

	public function detachTestCase($testPlanId, $testCaseVersionId)
	{
		return $this->testPlanRepository->detachTestCase($testPlanId, $testCaseVersionId);
	}

	public function getTestRuns($testPlanId)
	{
		return $this->testPlanRepository->findByTestPlan($testPlanId);
	}

}
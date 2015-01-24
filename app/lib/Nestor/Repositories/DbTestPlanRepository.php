<?php
namespace Nestor\Repositories;

use Nestor\Model\TestPlan;
use Nestor\Model\TestCaseVersion;

class DbTestPlanRepository extends DbBaseRepository implements TestPlanRepository {

	public function __construct(TestPlan $model)
	{
		parent::__construct($model);
	}

	/**
	 * Get all test plans that belong to a project
	 *
	 * @param  int   $projectId
	 * @return TestPlan
	 */
	public function findByProjectId($projectId)
	{
		return $this
			->model
			->where('project_id', $projectId)
			->paginate(10)
			->toArray()
		;
	}

	public function paginate($perPage = 0)
	{
		return $this
			->model
			->paginate($perPage)
			->toArray();
		;
	}

	public function paginateTestPlansForProjectWith($perPage, $projectId, array $with)
	{
		return $this
			->model
			->where('project_id', $projectId)
			->with($with)
			->paginate($perPage)
			->toArray();
		;
	}

	public function findForExecutionByProjectId($projectId)
	{
		return TestPlan::select('test_plans.*')
			->where('test_plans.project_id', $projectId)
			->join('test_plans_test_cases', 'test_plans.id', '=', 'test_plans_test_cases.test_plan_id')
			->groupBy('test_plans.id')
			->paginate(10)
			->toArray()
		;
	}

	public function assign($testPlanId, $testcaseVersionId, $userId)
	{
		return TestCaseVersion::find($testcaseVersionId)
			->testplans()
			->updateExistingPivot($testPlanId, array('assignee' => $userId), /*touch*/ true)
			->toArray()
		;
	}

}

<?php namespace Nestor\Repositories;

interface TestPlanRepository {

	/**
	 * Get all test plans that belong to a project
	 *
	 * @param  int   $projectId
	 * @return TestPlan
	 */
	public function findByProjectId($projectId);

	public function paginate($perPage);

	public function findForExecutionByProjectId($projectId);

	public function assign($testPlanId, $testcaseVersionId, $userId);

	public function paginateTestPlansForProjectWith($perPage, $projectId, array $with);

}
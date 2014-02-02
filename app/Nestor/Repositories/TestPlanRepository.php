<?php namespace Nestor\Repositories;

interface TestPlanRepository {

	/**
	 * Get all test plans
	 *
	 * @return TestPlan
	 */
	public function all();

	/**
	 * Get a TestPlan by their primary key.
	 *
	 * @param  int   $id
	 * @return TestPlan
	 */
	public function find($id);

	/**
	 * Get all test plans that belong to a project
	 *
	 * @param  int   $projectId
	 * @return TestPlan
	 */
	public function findByProjectId($projectId);

	/**
	 * Create a test plan
	 *
	 * @param  int     $project_id
	 * @param  string  $name
	 * @param  string  $description
	 * @return TestPlan
	 */
	public function create($project_id, $name, $description);

	/**
	 * Update a test plan
	 *
	 * @param  int     $id
	 * @param  int     $project_id
	 * @param  string  $name
	 * @param  string  $description
	 * @return TestPlan
	 */
	public function update($id, $project_id, $name, $description);

	/**
	 * Delete a test plan
	 *
	 * @param int $id
	 */
	public function delete($id);

}
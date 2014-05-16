<?php namespace Nestor\Repositories;

interface TestRunRepository {

	/**
	 * Get all test runs
	 *
	 * @return TestRun
	 */
	public function all();

	/**
	 * Get a TestRun by their primary key.
	 *
	 * @param  int   $id
	 * @return TestRun
	 */
	public function find($id);

	/**
	 * Get all test runs that belong to a test plan
	 *
	 * @param  int   $test_plan_id
	 * @return TestRun
	 */
	public function findByTestPlanId($test_plan_id);

	/**
	 * Create a test run
	 *
	 * @param  int     $test_plan_id
	 * @param  string  $name
	 * @param  string  $description
	 * @return TestRun
	 */
	public function create($test_plan_id, $name, $description);

	/**
	 * Update a test run
	 *
	 * @param  int     $id
	 * @param  int     $test_plan_id
	 * @param  string  $name
	 * @param  string  $description
	 * @return TestRun
	 */
	public function update($id, $test_plan_id, $name, $description);

	/**
	 * Delete a test run
	 *
	 * @param int $id
	 */
	public function delete($id);

	public function isNameAvailable($id, $testPlanId, $name);

}
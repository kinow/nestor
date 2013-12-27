<?php namespace Nestor\Repositories;

interface TestSuiteRepository {

	/**
	 * Get all test suites
	 *
	 * @return TestSuite
	 */
	public function all();

	/**
	 * Get a TestSuite by their primary key.
	 *
	 * @param  int   $id
	 * @return TestSuite
	 */
	public function find($id);

	/**
	 * Create a test suite
	 *
	 * @param  int     $project_id
	 * @param  string  $name
	 * @param  string  $description
	 * @return TestSuite
	 */
	public function create($project_id, $name, $description);

	/**
	 * Update a test suite
	 *
	 * @param  int     $id
	 * @param  int     $project_id
	 * @param  string  $name
	 * @param  string  $description
	 * @return TestSuite
	 */
	public function update($id, $project_id, $name, $description);

	/**
	 * Delete a test suite
	 *
	 * @param int $id
	 */
	public function delete($id);

}
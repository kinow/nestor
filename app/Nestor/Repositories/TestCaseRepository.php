<?php namespace Nestor\Repositories;

interface TestCaseRepository {

	/**
	 * Get all test cases
	 *
	 * @return TestCase
	 */
	public function all();

	/**
	 * Get a TestCase by their primary key.
	 *
	 * @param  int   $id
	 * @return TestCase
	 */
	public function find($id);

	/**
	 * Searches an entry by its name.
	 * @param string $name name
	 * @param bool $caseSensitive whether to ignore case sensitivy or not
	 * @return \TestCase2
	 */
	public function findByName($name, $caseSensitive);

	/**
	 * Create a test case
	 *
	 * @param  int     $project_id
	 * @param  int     $test_suite_id
	 * @param  int     $execution_type_id
	 * @param  string  $name
	 * @param  string  $description
	 * @return TestCase2
	 */
	public function create($project_id, $test_suite_id, $execution_type_id, $name, $description, $prerequisite);

	/**
	 * Update a test case
	 *
	 * @param  int     $project_id
	 * @param  int     $test_suite_id
	 * @param  int     $execution_type_id
	 * @param  string  $name
	 * @param  string  $description
	 * @return TestCase
	 */
	public function update($id, $project_id, $test_suite_id, $execution_type_id, $name, $description, $prerequisite);

	/**
	 * Delete a test case
	 *
	 * @param int $id
	 */
	public function delete($id);

}
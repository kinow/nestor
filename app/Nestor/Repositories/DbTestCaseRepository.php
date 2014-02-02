<?php namespace Nestor\Repositories;

use Auth, Hash, Validator;
use \TestCase2;

class DbTestCaseRepository implements TestCaseRepository {

	/**
	 * Get all of the test cases.
	 *
	 * @return array
	 */
	public function all()
	{
		return TestCase2::all();
	}

	/**
	 * Get a TestCase by their primary key.
	 *
	 * @param  int   $id
	 * @return TestCase
	 */
	public function find($id)
	{
		return TestCase2::findOrFail($id);
	}

	/**
	 * Create a test case
	 *
	 * @param  int     $project_id
	 * @param  int     $test_suite_id
	 * @param  int     $execution_type_id
	 * @param  string  $name
	 * @param  string  $description
	 * @return TestCase
	 */
	public function create($project_id, $test_suite_id, $execution_type_id, $name, $description)
	{
		return TestCase2::create(compact('project_id', 'test_suite_id', 'execution_type_id', 'name', 'description'));
	}

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
	public function update($id, $project_id, $test_suite_id, $execution_type_id, $name, $description)
	{
		$test_case = $this->find($id);

		$test_case->fill(compact('project_id', 'test_suite_id', 'execution_type_id', 'name', 'description'))->save();

		return $test_case;
	}

	/**
	 * Delete a test case
	 * @param int $id
	 */
	public function delete($id)
	{
		return TestCase2::where('id', $id)->delete();
	}

}

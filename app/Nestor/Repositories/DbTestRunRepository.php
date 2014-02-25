<?php namespace Nestor\Repositories;

use Auth, Hash, Validator;
use \TestRun;

class DbTestRunRepository implements TestRunRepository {

	/**
	 * Get all of the test runs.
	 *
	 * @return array
	 */
	public function all()
	{
		return TestRun::all();
	}

	/**
	 * Get a TestRun by their primary key.
	 *
	 * @param  int   $id
	 * @return TestRun
	 */
	public function find($id)
	{
		return TestRun::findOrFail($id);
	}

	/**
	 * Get all test runs that belong to a test plan
	 *
	 * @param  int   $test_plan_id
	 * @return TestRun
	 */
	public function findByTestPlanId($test_plan_id)
	{
		return TestRun::where('test_plan_id', $test_plan_id)->get();
	}

	/**
	 * Create a test run
	 *
	 * @param  int     $test_plan_id
	 * @param  string  $name
	 * @param  string  $description
	 * @return TestRun
	 */
	public function create($test_plan_id, $name, $description)
	{
		return TestRun::create(compact('test_plan_id', 'name', 'description'));
	}

	/**
	 * Update a test run
	 *
	 * @param  int     $id
	 * @param  int     $test_plan_id
	 * @param  string  $name
	 * @param  string  $description
	 * @return TestRun
	 */
	public function update($id, $test_plan_id, $name, $description)
	{
		$test_run = $this->find($id);

		$test_run->fill(compact('test_plan_id', 'name', 'description'))->save();

		return $test_run;
	}

	/**
	 * Delete a test run
	 *
	 * @param int $id
	 */
	public function delete($id)
	{
		return TestRun::where('id', $id)->delete();
	}

}

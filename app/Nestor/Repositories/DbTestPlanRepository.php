<?php namespace Nestor\Repositories;

use Auth, Hash, Validator;
use \TestPlan;

class DbTestPlanRepository implements TestPlanRepository {

	/**
	 * Get all of the test plans.
	 *
	 * @return array
	 */
	public function all()
	{
		return TestPlan::all();
	}

	/**
	 * Get a TestPlan by their primary key.
	 *
	 * @param  int   $id
	 * @return TestPlan
	 */
	public function find($id)
	{
		return TestPlan::findOrFail($id);
	}

	/**
	 * Create a test plan
	 *
	 * @param  int     $project_id
	 * @param  string  $name
	 * @param  string  $description
	 * @return TestPlan
	 */
	public function create($project_id, $name, $description)
	{
		return TestPlan::create(compact('project_id', 'name', 'description'));
	}

	/**
	 * Update a test plan
	 *
	 * @param  int     $id
	 * @param  int     $project_id
	 * @param  string  $name
	 * @param  string  $description
	 * @return TestPlan
	 */
	public function update($id, $project_id, $name, $description)
	{
		$test_plan = $this->find($id);

		$test_plan->fill(compact('project_id', 'name', 'description'))->save();

		return $test_plan;
	}

	/**
	 * Delete a test plan
	 * @param int $id
	 */
	public function delete($id)
	{
		return TestPlan::where('id', $id)->delete();
	}

}

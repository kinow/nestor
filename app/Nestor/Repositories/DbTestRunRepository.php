<?php namespace Nestor\Repositories;

use Auth, Hash, Validator;
use \TestRun;
use TestSuite;
use DB;

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

	public function isNameAvailable($id, $testPlanId, $name)
	{
		return TestRun::where('id', '<>', $id)
			->where('test_plan_id', '=', $testPlanId)
			->where(new \Illuminate\Database\Query\Expression("lower(test_runs.name)"), '=', strtolower($name))
			->count() == 0;
	}


	public function getTestSuites($testRunId)
	{
		return TestSuite::select('test_suites.*')
			->join('test_cases', 'test_suites.id', '=', 'test_cases.test_suite_id')
			->join('test_case_versions', 'test_case_versions.test_case_id', '=', 'test_cases.id')
			->join('test_plans_test_cases', 'test_plans_test_cases.test_case_version_id', '=', 'test_case_versions.id')
			->join('test_plans', 'test_plans_test_cases.test_plan_id', '=', 'test_plans.id')
			->join('test_runs', 'test_runs.test_plan_id', '=', 'test_plans.id')
			->where('test_runs.id', '=', $testRunId)
			->groupBy('test_suites.id');
	}

	public function getTestCases($testRunId)
	{
		return DB::select(DB::raw(
			"select test_case_versions.*, test_cases.test_suite_id, executions.execution_status_id, executions.notes " .
			"from test_cases " .
			"inner join test_case_versions on test_case_versions.test_case_id = test_cases.id " .
			"inner join executions on executions.test_case_version_id = test_case_versions.id " .
			"inner join test_runs on test_runs.id = executions.test_run_id " .
			"where test_runs.id = :test_run_id " .
			"group by test_case_versions.id, test_case_versions.version"
			), array(
		    'test_run_id' => $testRunId,
		));
	}

}

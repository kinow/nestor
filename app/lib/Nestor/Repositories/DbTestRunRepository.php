<?php
namespace Nestor\Repositories;

use Auth;
use Hash;
use Validator;
use TestRun;
use TestSuite;
use DB;

class DbTestRunRepository extends DbBaseRepository implements TestRunRepository
{

	public function __construct(TestRun $model)
	{
		parent::__construct($model);
	}

	public function findByTestPlan($test_plan_id)
	{
		return TestRun::where('test_plan_id', $test_plan_id)->get();
	}

	public function create($test_plan_id, $name, $description)
	{
		return TestRun::create(compact('test_plan_id', 'name', 'description'));
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

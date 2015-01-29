<?php
namespace Nestor\Model;

use DB;

class TestPlan extends BaseModel
{

	protected $table = 'test_plans';
	protected $fillable = array('id', 'name', 'description', 'project_id');
	protected $hidden = array('');

	protected static $purgeable = [''];

	protected static $_rules = array(
		"create" => array(
			'name' => 'required|min:2',
			'description' => '',
			'project_id' => 'required'
		),
		"update" => array()
	);

	public function project()
	{
		return $this->belongsTo('Project', 'project_id');
	}

	public function testruns()
	{
		return $this->hasMany('TestRun');
	}

	public function testCases()
	{
		return $this->belongsToMany('Nestor\\Model\\TestCaseVersion', 'test_plans_test_cases');
	}

	public function testcasesDetached()
	{
		$sql = <<<EOF
select tc.*, tcv.version 
from test_cases tc 
inner join test_case_versions tcv on tc.id = tcv.test_case_id 
inner join test_plans_test_cases tptc on tptc.test_case_version_id = tcv.id 
where tptc.test_plan_id = :test_plan_id 
group by tc.id 
EOF;
		$results = DB::select(DB::raw($sql), array('test_plan_id' => $this->id));
		return $results;
		// $collection = new \Illuminate\Database\Eloquent\Collection();
		// foreach ($results as $rawObject)
		// {
		//      $model = new Model();
		//      $collection->add($model->newFromBuilder($rawObject));
		// }
		// return $collection;
	}

	// public function testcases()
	// {
	// 	$testcases = TestCase2::
	// 		select('test_cases.*')
	// 		->join('test_case_versions', 'test_case_versions.test_case_id', '=', 'test_cases.id')
	// 		->join('test_plans_test_cases', 'test_plans_test_cases.test_case_version_id', '=', 'test_case_versions.id')
	// 		->where('test_plans_test_cases.test_plan_id', '=', $this->id)
	// 		->groupBy('test_cases.id');

	// 	return $testcases;
	// }

	// public function testcases()
	// {
	// 	$testcases = array();
	// 	$testcaseVersions = $this->testcaseVersions()->get();
	// 	foreach ($testcaseVersions as $testcaseVersion)
	// 	{
	// 		$testcases[] = $testcaseVersion->testcase()->first();
	// 	}
	// 	return new \Illuminate\Support\Collection($testcases);
	// }

	// public function testcaseVersions()
	// {
	// 	$testcases = TestCaseVersion::
	// 		select('test_case_versions.*')
	// 		->join('test_plans_test_cases', 'test_plans_test_cases.test_case_version_id', '=', 'test_case_versions.id')
	// 		->where('test_plans_test_cases.test_plan_id', '=', $this->id)
	// 		->groupBy('test_case_versions.test_case_id');

	// 	return $testcases;
	// }

	// FIXME: same as testcases() ?
	// public function testcaseVersions()
	// {
	// 	return $this->belongsToMany('TestCaseVersion', 'test_plans_test_cases', 'test_plan_id', 'test_case_version_id')
	// 		->withPivot('assignee')
	// 		->withTimestamps();
	// }

	public function hasExecutions()
	{
		return TestPlan::join('test_runs', 'test_runs.test_plan_id', '=', $this->id)
			->join('executions', 'executions.test_run_id', '=', 'test_runs.id')
			->count('test_plans.id') > 0;
	}

}
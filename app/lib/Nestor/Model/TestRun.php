<?php
namespace Nestor\Model;

class TestRun extends BaseModel {

	protected $table = 'test_runs';
	protected $fillable = array('id', 'name', 'description', 'test_plan_id');
	protected $hidden = array('');

	protected static $purgeable = [''];

	protected static $_rules = array(
		"create" => array(
				'name' => 'required|min:2',
				'description' => '',
				'test_plan_id' => 'required'
		),
		"update" => array(
				'name' => 'required|min:2',
				'description' => '',
				'test_plan_id' => 'required'
		)
	);

	public function testPlan()
	{
		return $this->belongsTo('Nestor\\Model\\TestPlan', 'test_plan_id');
	}

	public function executions()
	{
		return $this->hasMany('Nestor\\Model\\Execution', 'test_run_id');
	}

	public function countTestCases()
	{
		return static::join('test_plans', 'test_plans.id', '=', 'test_runs.test_plan_id')
			->join('test_plans_test_cases', 'test_plans_test_cases.test_plan_id', '=', 'test_plans.id')
			->join('test_case_versions', 'test_case_versions.id', '=', 'test_plans_test_cases.test_case_version_id')
			->join('test_cases', 'test_cases.id', '=', 'test_case_versions.test_case_id')
			->where('test_runs.id', '=', $this->id)
			->count('test_cases.id');
	}

	public function progress()
	{
		$percentage = 0;
		$progress = array();
		$total = $this->countTestCases();
		$executions = Execution::select('executions.*')
			->where('executions.test_run_id', $this->id)
			->join('test_case_versions', 'test_case_versions.id', '=', 'executions.test_case_version_id')
			->join('test_cases', 'test_cases.id', '=', 'test_case_versions.test_case_id')
			->groupBy('test_cases.id')->get();

		$executionStatuses = ExecutionStatus::all();
		$executionStatusesCount = array();
		foreach ($executionStatuses as $executionStatus)
		{
			$executionStatusesCount[$executionStatus->id] = 0;
		}
		foreach ($executions as $execution)
		{
			$executionStatusesCount[$execution->execution_status_id] += 1;
		}
		$executionStatusesCount[1] = count($executionStatusesCount) - $total;
		foreach ($executionStatusesCount as $statusId => $count)
		{
			$progress[$statusId] = $total ? ($count / $total) * 100 : 0;
		}
		$percentage = $total ? ($executions->count()/$total) * 100 : 0;
		return array(
			'percentage' => $percentage,
			'progress' => $progress
		);
	}

	// public function testcases()
	// {
	// 	return $this->belongsToMany('TestCase2', 'test_plans_test_cases', 'test_plan_id', 'test_case_id')
	// 			->withTimestamps();
	// }

}
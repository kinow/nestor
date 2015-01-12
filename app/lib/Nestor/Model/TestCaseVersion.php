<?php
namespace Nestor\Model;

public class TestCaseVersion extends BaseModel
{
	protected $table = 'test_case_versions';
	protected $fillable = array('id', 'version', 'name', 'description', 'prerequisite', 'test_case_id', 'execution_type_id');
	protected $hidden = array('');

	protected static $purgeable = [''];

	protected static $_rules = array(
		"create" => array(
			'version' => 'required|numeric|min:1',
			'name' => 'required|min:2',
			'description' => '',
			'prerequisite' => '',
			'test_case_id' => 'required|numeric',
			'execution_type_id' => 'required'
		),
		"update" => array(
		),
	);

	public function testcase()
	{
		return $this->belongsTo('TestCase2', 'test_case_id');
	}

	public function executionType()
	{
		return $this->belongsTo('ExecutionType', 'execution_type_id');
	}

	public function executions()
	{
		return $this->hasMany('Execution', 'test_case_id');
	}

	public function steps()
	{
		return $this->belongsToMany('TestCaseStep', 'test_case_step_versions');
	}

	public function sortedSteps()
	{
		return TestCaseVersion::
			hasMany('TestCaseStepVersion', 'test_case_version_id', 'id');
	}

	public function labels()
	{
		return $this->belongsToMany('Label', 'test_case_versions_labels')->withTimestamps();
	}

	public function testplans()
	{
		return $this->belongsToMany('TestPlan', 'test_plans_test_cases', 'test_case_version_id', 'test_plan_id')
			->withPivot('assignee')
			->withTimestamps();
	}

	public function assignee()
	{
		return $this->pivot->assignee;
	}
}
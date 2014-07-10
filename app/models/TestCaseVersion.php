<?php

use Magniloquent\Magniloquent\Magniloquent;

class TestCaseVersion extends Magniloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'test_case_versions';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array('id', 'version', 'name', 'description', 'prerequisite', 'test_case_id', 'execution_type_id');

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('');

	protected static $rules = array(
		"save" => array(
			'version' => 'required|numeric|min:1',
			'name' => 'required|min:2',
			'description' => '',
			'prerequisite' => '',
			'test_case_id' => 'required|numeric',
			'execution_type_id' => 'required'
		),
		"create" => array(
		),
		"update" => array(
		),
	);

	protected static $relationships = array(
		'testcase' => array('belongsTo', 'TestCase2', 'test_case_id'),
		'executionType' => array('belongsTo', 'ExecutionType', 'execution_type_id'),
		'executions' => array('hasMany', 'Execution', 'test_case_id'),
		'steps' => array('belongsToMany', 'TestCaseStep', 'test_case_step_versions'),
		'labels' => array('belongsToMany', 'Label', 'test_case_versions_labels')
	);

	protected static $purgeable = [''];
	
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
<?php

use Magniloquent\Magniloquent\Magniloquent;

class TestPlan extends Magniloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'test_plans';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array('id', 'name', 'description', 'project_id');

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('');

	protected static $rules = array(
		"save" => array(
				'name' => 'required|min:2',
				'description' => '',
				'project_id' => 'required'
		),
		"create" => array(
				'name' => 'unique:test_plans,name,project_id,:project_id|required|min:2',
				'description' => '',
				'project_id' => 'required'
		),
		"update" => array()
	);

	protected static $relationships = array(
		'project' => array('belongsTo', 'Project', 'project_id')
	);

	protected static $purgeable = [''];

	public function testcases()
	{
		return $this->belongsToMany('TestCase2', 'test_plans_test_cases', 'test_plan_id', 'test_case_id')
				->withTimestamps();
	}

	public function testruns()
	{
		return $this->hasMany('TestRun');
	}

}
<?php

use Magniloquent\Magniloquent\Magniloquent;

class TestRun extends Magniloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'test_runs';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array('id', 'name', 'description', 'test_plan_id');

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
				'test_plan_id' => 'required'
		),
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

	protected static $relationships = array(
		'testplan' => array('belongsTo', 'TestPlan', 'test_plan_id')
	);

	protected static $purgeable = [''];

	public function testplan()
	{
		return $this->belongsTo('TestPlan', 'test_plan_id');
	}

	// public function testcases()
	// {
	// 	return $this->belongsToMany('TestCase2', 'test_plans_test_cases', 'test_plan_id', 'test_case_id')
	// 			->withTimestamps();
	// }

}
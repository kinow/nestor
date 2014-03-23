<?php

use Magniloquent\Magniloquent\Magniloquent;
use \Execution;

class TestCaseStep extends Magniloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'test_case_steps';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array('id', 'order', 'description', 'test_case_id', 'expected_result', 'execution_status_id');

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
				'prerequisite' => '',
				'test_suite_id' => 'required',
				'project_id' => 'required',
				'execution_type_id' => 'required'
		),
		"create" => array(
				'name' => 'unique:test_cases,name,test_suite_id,:test_suite_id|required|min:2',
				'description' => '',
				'prerequisite' => '',
				'test_suite_id' => 'required',
				'project_id' => 'required',
				'execution_type_id' => 'required'
		),
		"update" => array(
				'name' => 'unique:test_cases,name,test_suite_id,:test_suite_id|required|min:2',
				'description' => '',
				'prerequisite' => '',
				'test_suite_id' => 'required',
				'project_id' => 'required',
				'execution_type_id' => 'required'
		)
	);

	protected static $relationships = array(
		'project' => array('belongsTo', 'Project', 'project_id'),
		'testSuite' => array('belongsTo', 'TestSuite', 'test_suite_id'),
		'executionType' => array('belongsTo', 'ExecutionType', 'execution_type_id')
	);

	protected static $purgeable = [''];

	public function executionType()
	{
		return $this->belongsTo('ExecutionType', 'execution_type_id')->first();
	}

	public function lastExecutionStatus() 
	{
		return Execution::where('test_case_id', $this->id)
			->orderBy('id', 'DESC')
			->first();
	}

}
<?php

use Magniloquent\Magniloquent\Magniloquent;
use \Execution;

class TestCase2 extends Magniloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'test_cases';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array('id', 'name', 'description', 'prerequisite', 'test_suite_id', 'project_id', 'execution_type_id');

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
				'lower(name)' => 'unique:test_cases,lower(name),test_suite_id,:test_suite_id',
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
		'executionType' => array('belongsTo', 'ExecutionType', 'execution_type_id'),
		'steps' => array('hasMany', 'TestCaseStep', 'test_case_id')
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
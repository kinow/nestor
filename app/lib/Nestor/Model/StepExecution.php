<?php
namespace Nestor\Model;

class StepExecution extends BaseModel 
{

	protected $table = 'step_executions';
	protected $fillable = array('id', 'execution_id', 'test_case_step_version_id', 'execution_status_id');
	protected $hidden = array('');

	protected static $_rules = array(
		"create" => array(
				'execution_id' => 'required|numeric',
				'test_case_step_version_id' => 'required|numeric',
				'execution_status_id' => 'required|numeric'
		),
		"update" => array(
		)
	);

	public function executionStatus()
	{
		return $this->belongsTo('ExecutionStatus', 'execution_status_id');
	}

	public function testCaseStepVersion()
	{
		return $this->belongsTo('TestCaseStepVersion', 'test_case_step_version_id');
	}

}
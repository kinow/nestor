<?php
namespace Nestor\Model;

class TestCaseStepVersion extends BaseModel
{

	protected $table = 'test_case_step_versions';
	protected $fillable = array('id', 'version', 'test_case_version_id', 'test_case_step_id', 'order', 'description', 'expected_result', 'execution_status_id');

	protected $hidden = array('');
	protected static $purgeable = [''];

	protected static $_rules = array(
		"create" => array(
				'version' => 'required|numeric|min:1',
				'test_case_version_id' => 'required|numeric',
				'test_case_step_id' => 'required|numeric',
				'order' => 'required|numeric',
				'description' => '',
				'expected_result' => '',
				'execution_status_id' => 'required|numeric'
		),
		"update" => array(
		)
	);

	public function testCaseVersion()
	{
		return $this->belongsTo('TestCaseVersion', 'test_case_version_id');
	}
	
	public function testCaseStep()
	{
		return $this->belongsTo('TestCaseStep', 'test_case_step_id');
	}

	public function executionStatus()
	{
		return $this->belongsTo('ExecutionStatus', 'execution_status_id');
	}

	public function executions()
	{
		return $this->hasMany('StepExecution', 'test_case_step_id');
	}
	
}
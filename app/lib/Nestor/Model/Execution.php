<?php
namespace Nestor\Model;

class Execution extends BaseModel
{

	protected $table = 'executions';
	protected $fillable = array('id', 'test_run_id', 'test_case_version_id', 'execution_status_id', 'notes');

	protected $hidden = array('');

	protected static $_rules = array(
		"create" => array(
			'test_run_id' => 'required|numeric',
			'test_case_version_id' => 'required|numeric',
			'execution_status_id' => 'required|numeric',
			'notes' => ''
		),
		"update" => array(
		)
	);

	public function executionStatus()
	{
		return $this->belongsTo('Nestor\\Model\\ExecutionStatus', 'execution_status_id');
	}

}
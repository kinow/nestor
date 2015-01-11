<?php
namespace Nestor\Model;

class ExecutionStatus extends BaseModel
{

	const NOT_RUN = 1;
	const PASSED = 2;
	const FAILED = 3;
	const BLOCKED = 4;

	protected $table = 'execution_statuses';
	protected $fillable = array('id', 'name', 'description');
	protected $hidden = array('');

	protected static $_rules = array(
		"create" => array(
				'name' => 'unique:execution_statuses,name|required|min:2',
				'description' => ''
		),
		"update" => array(
				'name' => 'unique:execution_statuses,name|required|min:2',
				'description' => ''
		)
	);

}
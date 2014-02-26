<?php

use Magniloquent\Magniloquent\Magniloquent;

class ExecutionStatus extends Magniloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'execution_statuses';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array('id', 'name', 'description');

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('');

	protected static $rules = array(
		"save" => array(
				'name' => 'required|min:2',
				'description' => ''
		),
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
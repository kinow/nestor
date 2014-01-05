<?php

use Magniloquent\Magniloquent\Magniloquent;

class ExecutionType extends Magniloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'execution_types';

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
				'name' => 'unique:execution_types|required|min:2',
				'description' => ''
		),
		"update" => array()
	);

	protected static $relationships = array(
		'testCases' => array('hasMany', 'TestCase'),
	);

	protected static $purgeable = [''];

}
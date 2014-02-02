<?php

use Magniloquent\Magniloquent\Magniloquent;

class TestSuite extends Magniloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'test_suites';

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
				'project_id' => 'required|numeric'
		),
		"create" => array(
				'name' => 'unique:test_suites,name,project_id,:project_id|required|min:2',
				'description' => '',
				'project_id' => ''
		),
		"update" => array()
	);

	protected static $relationships = array(
		'project' => array('belongsTo', 'Project', 'project_id')
	);

	protected static $purgeable = [''];

}
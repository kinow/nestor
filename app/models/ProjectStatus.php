<?php

use Magniloquent\Magniloquent\Magniloquent;

class ProjectStatus extends Magniloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'project_statuses';

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
				'name' => 'unique:projects|required|min:2',
				'description' => ''
		),
		"update" => array()
	);

	protected static $relationships = array(
		'projects' => array('hasMany', 'Project'),
	);

	protected static $purgeable = [''];

}
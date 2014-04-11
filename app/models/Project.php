<?php

use Magniloquent\Magniloquent\Magniloquent;

class Project extends Magniloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'projects';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array('name', 'description', 'project_statuses_id');

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
				'project_statuses_id' => 'required|numeric'
		),
		"create" => array(
				'name' => 'unique:projects|required|min:2',
				'description' => '',
				'project_statuses_id' => 'required|numeric'
		),
		"update" => array()
	);

	protected static $relationships = array(
		'projectStatus' => array('belongsTo', 'ProjectStatus', 'project_statuses_id'),
	);

	protected static $purgeable = [''];

}
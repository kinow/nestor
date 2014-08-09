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
				'name' => 'required|min:2',
				'description' => '',
				'project_id' => ''
		),
		"update" => array()
	);

	protected static $relationships = array(
		'project' => array('belongsTo', 'Project', 'project_id')
	);

	protected static $purgeable = [''];

	public function labels() 
	{
		return $this->belongsToMany('Label', 'test_suites_labels')->withTimestamps();
	}

}
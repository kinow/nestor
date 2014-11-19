<?php namespace Nestor\Model;

class Project extends BaseModel {

	protected $table = 'projects';

	protected $fillable = array('name', 'description', 'project_statuses_id');

	protected static $_rules = array(
		"create" => array(
			'name' => 'unique:projects|required|min:2',
			'description' => '',
			'project_statuses_id' => 'required|numeric'
		),
		"update" => array(
			'name' => 'required|min:2',
			'description' => '',
			'project_statuses_id' => 'sometimes|numeric'
		)
	);


	public function projectStatus()
	{
		return $this->belongsTo('ProjectStatus', 'project_statuses_id');
	}

	public function testsuites()
	{
		return $this->hasMany('TestSuite', 'project_id');
	}

}
<?php namespace Nestor\Model;

class ProjectStatus extends BaseModel {

	protected $table = 'project_statuses';

	protected $fillable = array('id', 'name', 'description');

	const ACTIVE = 1;
	const INACTIVE = 2;

	protected static $_rules = array(
		"create" => array(
			'name' => 'required|min:2',
			'description' => ''
		),
		"update" => array(
			'name' => 'required|min:2',
			'description' => ''
		)
	);

	public function projects()
	{
		return $this->hasMany('Project');
	}

}
<?php
namespace Nestor\Model;

class Label extends BaseModel 
{

	protected $table = 'labels';

	protected $fillable = array('project_id', 'name', 'color');

	protected $hidden = array('');

	protected static $_rules = array(
		"create" => array(
			'project_id' => 'required|numeric',
			'name' => 'required|min:1',
			'color' => ''
		),
		"update" => array(
		)
	);

	public function project()
	{
		return $this->belongsTo('Project', 'project_id');
	}

	public function testSuites()
	{
		return $this->belongsToMany('test_suites_labels');
	}

}
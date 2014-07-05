<?php

use Magniloquent\Magniloquent\Magniloquent;

class Label extends Magniloquent {

	protected $table = 'labels';

	protected $fillable = array('project_id', 'name', 'color');

	protected static $relationships = array(
		'project' => array('belongsTo', 'Project', 'project_id'),
		'testsuites' => array('belongsToMany', 'test_suites_labels')
	);

	protected $hidden = array('');

	protected static $rules = array(
		"save" => array(
			'project_id' => 'required|numeric',
			'name' => 'required|min:1',
			'color' => ''
		),
		"create" => array(
		),
		"update" => array(
		)
	);

}

<?php

use Magniloquent\Magniloquent\Magniloquent;

class ReportType extends Magniloquent {

	protected $table = 'report_types';

	protected $fillable = array('id', 'name', 'description');

	protected static $relationships = array(
		'reports' => array('hasMany', 'Report', 'report_type_id')
	);

	protected $hidden = array('');

	protected static $rules = array(
		"save" => array(
			'name' => 'required|min:1',
			'description' => ''
		),
		"create" => array(
		),
		"update" => array(
		)
	);

}
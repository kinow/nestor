<?php

use Magniloquent\Magniloquent\Magniloquent;

class Report extends Magniloquent {

	protected $table = 'reports';

	protected $fillable = array('id', 'report_type_id', 'name', 'description', 'script');

	protected static $relationships = array(
		'reportType' => array('belongsTo', 'ReportType')
	);

	protected $hidden = array('');

	protected static $rules = array(
		"save" => array(
			'name' => 'required|min:1',
			'report_type_id' => 'required|numeric|min:1',
			'script' => 'required|min:1',
			'description' => ''
		),
		"create" => array(
		),
		"update" => array(
		)
	);

}
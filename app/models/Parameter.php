<?php

use Magniloquent\Magniloquent\Magniloquent;

class Parameter extends Magniloquent {

	protected $table = 'parameters';

	protected $fillable = array('id', 'parameter_type_id', 'report_id', 'name', 'description');

	protected static $relationships = array(
		'report' => array('belongsTo', 'Report'),
		'parameterType' => array('belongsTo', 'ParameterType')
	);

	protected $hidden = array('');

	protected static $rules = array(
		"save" => array(
			'name' => 'required|min:1',
			'parameter_type_id' => 'required|numeric|min:1',
			'report_id' => 'required|numeric|min:1',
			'description' => ''
		),
		"create" => array(
		),
		"update" => array(
		)
	);

}
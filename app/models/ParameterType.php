<?php

use Magniloquent\Magniloquent\Magniloquent;

class ParameterType extends Magniloquent {

	protected $table = 'parameter_types';

	protected $fillable = array('id', 'name');

	protected static $relationships = array(
		'parameters' => array('hasMany', 'Parameter', 'parameter_type_id')
	);

	protected $hidden = array('');

	protected static $rules = array(
		"save" => array(
			'name' => 'required|min:1'
		),
		"create" => array(
		),
		"update" => array(
		)
	);

}
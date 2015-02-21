<?php
namespace Nestor\Model;

class ParameterType extends BaseModel
{

	protected $table = 'parameter_types';
	protected $fillable = array('id', 'name');

	protected static $_rules = array(
		"create" => array(
			'name' => 'required|min:1'
		),
		"update" => array(
			'name' => 'required|min:1'
		)
	);

	public function parameters()
	{
		return $this->hasMany('Nestor\\Model\\Parameter', 'parameter_type_id');
	}

}
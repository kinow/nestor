<?php
namespace Nestor\Model;

use Nestor\Model\Report;
use Nestor\Model\ParameterType;

class Parameter extends BaseModel
{

	protected $table = 'parameters';
	protected $fillable = array('id', 'parameter_type_id', 'report_id', 'name', 'description');
	protected $hidden = array('');
	
	protected static $_rules = array(
		"create" => array(
			'name' => 'required|min:1',
			'parameter_type_id' => 'required|numeric|min:1',
			'report_id' => 'required|numeric|min:1',
			'description' => ''
		),
		"update" => array(
		)
	);

	public function report()
	{
		return $this->belongsTo('Nesto\\Model\\Report');
	}

	public function parameterType()
	{
		return $this->belongsTo('Nesto\\Model\\ParameterType');
	}

}
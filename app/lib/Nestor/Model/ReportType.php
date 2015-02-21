<?php
namespace Nestor\Model;

class ReportType extends BaseModel {

	protected $table = 'report_types';
	protected $fillable = array('id', 'name', 'description');

	protected static $_rules = array(
		"create" => array(
			'name' => 'required|min:1',
			'description' => ''
		),
		"update" => array(
		)
	);

	public function reports()
	{
		return $this->hasMany('Nestor\\Model\\Report', 'report_type_id');
	}

}
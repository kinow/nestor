<?php
namespace Nestor\Model;

class Report extends BaseModel
{

	protected $table = 'reports';
	protected $fillable = array('id', 'report_type_id', 'name', 'description', 'script');

	protected static $_rules = array(
		"create" => array(
			'name' => 'required|min:1',
			'report_type_id' => 'required|numeric|min:1',
			'script' => 'required|min:1',
			'description' => ''
		),
		"update" => array(
		)
	);

	public function reportType()
	{
		return $this->belongsTo('Nestor\\Model\\ReportType');
	}

}
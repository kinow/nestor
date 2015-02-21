<?php
namespace Nestor\Model;

class TestSuite extends BaseModel 
{

	protected $table = 'test_suites';
	protected $fillable = array('id', 'name', 'description', 'project_id');
	protected $hidden = array('');
	protected static $purgeable = [''];

	protected static $_rules = array(
		"create" => array(
				'name' => 'required|min:2',
				'description' => '',
				'project_id' => 'required|numeric'
		),
		"update" => array(
				'name' => 'required|min:2',
				'description' => '',
				'project_id' => 'required|numeric'
		)
	);

	public function project()
	{
		return $this->belongsTo('Nestor\\Model\\Project', 'project_id');
	}

	public function labels() 
	{
		return $this->belongsToMany('Nestor\\Model\\Label', 'test_suites_labels')->withTimestamps();
	}

}
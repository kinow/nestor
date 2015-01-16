<?php
namespace Nestor\Model;

class TestCase2 extends BaseModel
{

	protected $table = 'test_cases';
	protected $fillable = array('id', 'test_suite_id', 'project_id');
	protected $hidden = array('');
	protected static $purgeable = [];

	protected static $_rules = array(
		"create" => array(
			'test_suite_id' => 'required',
			'project_id' => 'required'
		),
		"update" => array(
			'test_suite_id' => 'required',
			'project_id' => 'required'
		),
	);

	public function project()
	{
		return $this->belongsTo('Nestor\\Model\\Project', 'project_id');
	}

	public function testSuite()
	{
		return $this->belongsTo('Nestor\\Model\\TestSuite', 'test_suite_id');
	}

	public function testCaseVersions()
	{
		return $this->hasMany('Nestor\\Model\\TestCaseVersion', 'test_case_id');
	}

	public function latestVersion()
	{
		return $this->hasMany('Nestor\\Model\\TestCaseVersion', 'test_case_id')
			->orderBy('version', 'desc')
			->take(1)
			->firstOrFail();
	}

	public function steps()
	{
		return $this->hasManyThrough('Nestor\\Model\\TestCaseStepVersion', 'Nestor\\Model\\TestCaseVersion', 'test_case_id', 'test_case_version_id');
	}

}
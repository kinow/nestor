<?php
namespace Nestor\Model;

class TestCaseStep extends BaseModel
{
	protected $table = 'test_case_steps';
	protected $fillable = array('id');
	protected $hidden = array('');

	protected static $purgeable = [''];

	protected static $_rules = array(
		"create" => array(
		),
		"update" => array(
		)
	);

	public function testCaseStepVersions()
	{
		return $this->hasMany('Nestor\\Model\\TestCaseStepVersion', 'test_case_step_id');
	}

}
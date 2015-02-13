<?php
namespace Nestor\Model;

class ExecutionType extends BaseModel 
{
	const MANUAL = 1;
	const AUTOMATED = 2;

	protected $table = 'execution_types';
	protected $fillable = array('id', 'name', 'description');
	protected $hidden = array('');
	protected static $purgeable = [''];

	protected static $_rules = array(
		"create" => array(
			'name' => 'unique:execution_types|required|min:2',
			'description' => ''
		),
		"update" => array(
			'name' => 'required|min:2',
			'description' => ''
		)
	);

	public function testCases()
	{
		return $this->hasMany('Nestor\\Model\\TestCase');
	}
	
}
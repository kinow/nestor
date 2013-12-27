<?php

use Magniloquent\Magniloquent\Magniloquent;

class NavigationTreeNode extends Magniloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'navigation_tree';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array('id', 'node_id', 'node_type_id', 'parent_id', 'display_name');

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('');

	protected static $rules = array(
		"save" => array(
				'node_id' => 'required|numeric',
				'node_type_id' => 'required|numeric',
				'parent_id' => '',
				'display_name' => 'required|min:2'
		),
		"create" => array(
				'node_id' => 'required|numeric',
				'node_type_id' => 'required|numeric',
				'parent_id' => '',
				'display_name' => 'required|min:2'
		),
		"update" => array()
	);

	protected static $relationships = array(
		'projects' => array('hasMany', 'TestCase'),
	);

	protected static $purgeable = [''];

}
<?php

use Magniloquent\Magniloquent\Magniloquent;

class NavigationTreeNode extends Magniloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'navigation_tree';

	protected $primaryKey = 'ancestor';

	public $incrementing = FALSE;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array('node_id', 'node_type_id', 'display_name');

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('');

	protected static $rules = array(
		"save" => array(
				'ancestor' => 'required',
				'descendant' => 'required',
				'length' => '',
				'node_id' => 'required|numeric',
				'node_type_id' => 'required|numeric',
				'display_name' => 'required|min:2'
		),
		"create" => array(
				'ancestor' => 'required',
				'descendant' => 'required',
				'length' => '',
				'node_id' => 'required|numeric',
				'node_type_id' => 'required|numeric',
				'display_name' => 'required|min:2'
		),
		"update" => array()
	);

// 	protected static $relationships = array(
// 		'projects' => array('hasMany', 'TestCase'),
// 	);

	protected static $purgeable = [''];

}
<?php 

class PluginCategory extends Magniloquent {
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'plugin_categories';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array('name', 'description');

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('');

	protected static $rules = array(
		"save" => array(
			'name' => 'required|min:2',
			'description' => ''
		),
		"create" => array(
			'name' => 'unique:plugin_categories|required|min:2',
			'description' => ''
		),
		"update" => array(
			'name' => 'unique:plugin_categories|required|min:2',
			'description' => ''
		)
	);

	protected static $relationships = array(
		'plugins' => array('hasMany', 'Plugin', 'plugin_category_id'),
	);

	protected static $purgeable = [''];

}


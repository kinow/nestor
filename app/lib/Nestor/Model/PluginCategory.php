<?php 
namespace Nestor\Model;

class PluginCategory extends BaseModel
{
	
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

	protected static $_rules = array(
		"create" => array(
			'name' => 'unique:plugin_categories|required|min:2',
			'description' => ''
		),
		"update" => array(
			'name' => 'unique:plugin_categories|required|min:2',
			'description' => ''
		)
	);

	public function plugins()
	{
		return $this->hasMany('Nestor\\Model\\Plugin', 'plugin_category_id');
	}

}


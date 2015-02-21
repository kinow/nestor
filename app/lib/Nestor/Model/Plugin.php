<?php 
namespace Nestor\Model;

class Plugin extends BaseModel
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'plugins';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array('name', 'slug', 'description', 'version', 'author', 'url', 'status', 'released_at', 'plugin_category_id');

	/**
	 * Filled in runtime. This holds the associative array of interfaces and implementations provided by this 
	 * plugin. This should be filled by the plug-in manager during startup or when the cache is rebuilt.
	 */
	public $provides = array();

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('provides');

	protected static $_rules = array(
		"create" => array(
			'name' => 'required|unique:plugins|min:1',
			'slug' => 'required|unique:plugins|min:1',
			'description' => 'required|min:1',
			'version' => 'required|min:1',
			'author' => 'required|min:1',
			'url' => 'url', 
			'status' => 'required|min:1',
			'released_at' => 'required|date', 
			'plugin_category_id' => 'required|numeric'
		),
		"update" => array(
			
		)
	);

	public function pluginCategory()
	{
		return $this->belongsTo('Nestor\\Model\\PluginCategory', 'plugin_category_id');
	}
}

<?php namespace Nestor\Model;

use \Config;
use \App;

/**
 * Nestor application.
 */
class Nestor {

	/**
	 * Nestor plugins manager.
	 */
	protected $pluginManager = NULL;

	/**
	 * Constructor.
	 * 	
     * @return Nestor
	 */
	public function __construct()
	{
		$this->pluginManager = new PluginManager(App::make('Nestor\Repositories\PluginRepository'), App::make('Nestor\Repositories\PluginCategoryRepository'));
	}

	/**
	 * Application version.
	 */ 
	public function getVersion()
	{
		return '0.12';
	}

	// FIXME: replace by getThemeManager?
	public function getAvailableThemes()
	{
		$themes = array();
		$theme_config = Config::get('theme::config');
		$theme_path = $theme_config['themeDir'];
		$path = app('path.public').'/'.$theme_path.'/';
		if ($handle = opendir($path))
		{
			while (false !== ($entry = readdir($handle))) {
				if (is_dir($path . $entry) && '.' !== $entry && '..' !== $entry) {
					$themes[] = $entry;
				}
			}
		}
		return $themes;
	}

	/**
	 * Returns the plugin manager.
	 */
	public function getPluginManager()
	{
		return $this->pluginManager;
	}

}

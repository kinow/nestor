<?php

use \DB;
use \Config;
use Nestor\Repositories\PluginCategoryRepository;
use Nestor\Repositories\PluginRepository;

/**
 * Plug-ins and plug-in categories seeder.
 */
class PluginSeeder extends Seeder {

	protected $pluginCategories = NULL;
	protected $plugins = NULL;

	public function __construct(Nestor\Repositories\PluginCategoryRepository $pluginCategories, 
			Nestor\Repositories\PluginRepository $plugins)
	{
		$this->pluginCategories = $pluginCategories;
		$this->plugins = $plugins;
	}

	public function run()
	{
		DB::table('plugins')->delete();
		DB::table('plugin_categories')->delete();

		$this->pluginCategories->create(
			'Editors',
			'UI editors'
		);
		
	}

}
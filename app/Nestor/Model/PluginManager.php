<?php namespace Nestor\Model;

use \Plugin;
use Nestor\Repositories\PluginRepository;
use Nestor\Repositories\PluginCategoryRepository;

class PluginManager {

	protected $pluginsRepository;

	protected $pluginCategoriesRepository;

	public function __construct(PluginRepository $pluginsRepository, PluginCategoryRepository $pluginCategoriesRepository)
	{
		$this->pluginsRepository = $pluginsRepository;
		$this->pluginCategoriesRepository = $pluginCategoriesRepository;
	}

	public function getCategories()
	{
		return $this->pluginCategoriesRepository->all();
	}

	public function getByCategory() 
	{
		// plugins + easy load implementations
		
	}

	public function upload()
	{

	}

	public function activate(Plugin $plugin)
	{
		
	}

	public function deactivate(Plugin $plugin)
	{

	}

	public function install(Plugin $plugin)
	{

	}

	public function uninstall(Plugin $plugin)
	{

	}

	public function wipeout(Plugin $plugin)
	{

	}

	public function rebuildCache()
	{
		
		Cache::forever('plugins', $plugins);
	}

	public function getProviders($interface)
	{
		Log::debug(sprintf('Getting %s providers from plugin cache', $interface));
		// call the cache to get the providers
		if (Cache::has('plugins')) 
		{
			$plugins = Cache::get('plugins');
			if (isset($plugins[$interface]))
			{
				return $plugins[$interface];
			}
			else
			{
				Log::debug(sprintf('No providers for %s found', $interface));
				return array();
			}
		}
		Log::warn('Empty plug-in cache');
		return array();
	}

}
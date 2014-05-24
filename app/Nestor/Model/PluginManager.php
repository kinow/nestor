<?php namespace Nestor\Model;

use Plugin;
use Cache;
use Log;
use Nestor\Repositories\PluginRepository;
use Nestor\Repositories\PluginCategoryRepository;
use Symfony\Component\Finder\Finder;
use Composer\Json\JsonFile;

class PluginManager {

	protected $pluginsRepository;

	protected $pluginCategoriesRepository;

	public function __construct(PluginRepository $pluginsRepository, PluginCategoryRepository $pluginCategoriesRepository)
	{
		$this->pluginsRepository = $pluginsRepository;
		$this->pluginCategoriesRepository = $pluginCategoriesRepository;
		$this->finder = new Finder();
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
		$pluginProviders = array();
		if (is_dir($plugins = __DIR__.'/../../../plugins'))
		{
			$iterator = $this->finder
			  ->files()
			  ->name('composer.json')
			  ->depth(2)
			  ->in($plugins)
			  ->followLinks();
			foreach ($iterator as $file) {
				$jsonFile = new JsonFile($file->getRealpath());
				$contents = $jsonFile->read();
				if (!isset($contents['extra']) || !isset($contents['extra']['nestorqa']))
				{
					Log::debug("Skipping workbench: Not a Nestor-QA Plug-in");
					continue;
				}

				$nestorqaInfo = $contents['extra']['nestorqa'];
				if (isset($nestorqaInfo['provides']))
				{
					foreach ($nestorqaInfo['provides'] as $interface => $classes)
					{
						Log::debug(sprintf("Entry %s found for %s", $classes, $interface));
						$entry = NULL;
						if (isset($pluginProviders[$interface]))
						{
							$entry = $pluginProviders[$interface];
							if (!in_array($classes))
							{
								$entry[] = $classes;
							}
						}
						else
						{
							$entry = is_array($classes) ? $classes : array($classes);
							$pluginProviders[$interface] = $entry;
						}
					}
				}
			}
		} 
		else
		{
			throw new Exception('Missing plugins folder');
		}
		Cache::forever('pluginProviders', $pluginProviders);
	}

	public function getProviders($interface)
	{
		Log::debug(sprintf('Getting %s providers from plugin cache', $interface));
		// call the cache to get the providers
		if (Cache::has('pluginProviders')) 
		{
			$pluginProviders = Cache::get('pluginProviders');
			if (isset($pluginProviders[$interface]))
			{
				return $pluginProviders[$interface];
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
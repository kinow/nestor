<?php 
namespace Nestor\Model;

use Plugin;
use Cache;
use Log;
use DB;
use Exception;
use Nestor\Repositories\PluginRepository;
use Nestor\Repositories\PluginCategoryRepository;
use Symfony\Component\Finder\Finder;
use Composer\Json\JsonFile;

class PluginManager {

	protected $plugins;

	protected $pluginCategories;

	public function __construct(PluginRepository $plugins, PluginCategoryRepository $pluginCategories)
	{
		$this->plugins = $plugins;
		$this->pluginCategories = $pluginCategories;
		$this->finder = new Finder();
	}

	public function getCategories()
	{
		return $this->pluginCategories->all();
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
		Cache::forget('plugins');
		$pluginsCache = array();
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

				$name = $contents['name'];
				if ($index = strpos($name, '/'))
					$slug = substr($name, $index, strlen($name));
				else
					$slug = $name;
				$description = isset($contents['description']) ? $contents['description'] : '';
				$version = $contents['version'];
				$authors = isset($contents['authors']) ? json_encode($contents['authors']) : json_encode(array());
				$url = isset($contents['homepage']) ? $contents['homepage'] : '';
				$status = 'INSTALLED'; // TODO: constants
				$releasedAt = isset($contents['time']) ? $contents['time'] : '';
				$nestorqaInfo = $contents['extra']['nestorqa'];
				$categoryId = $nestorqaInfo['category_id'];

				$plugin = $this->plugins->findByName($name);
				Log::info('Reloading existing plug-in');
				if (!$plugin)
				{
					Log::debug(sprintf('Plug-in %s not found in database', $name));
					Log::info('Creating new plug-in');
					$plugin = $this->plugins->create($name, $slug, $description, $version, $authors, $url, $status, $releasedAt, $categoryId);
					if (!$plugin->isValid() || !$plugin->isSaved())
						throw new Exception(var_export($plugin->errors(), TRUE));
				}

				if (isset($nestorqaInfo['provides']))
				{
					foreach ($nestorqaInfo['provides'] as $interface => $classes)
					{
						Log::debug(sprintf("Entry %s found for %s", $classes, $interface));
						$entry = NULL;
						if (isset($plugin->provides[$interface]))
						{
							$entry = $plugin->provides[$interface];
							if (!in_array($classes))
							{
								$entry[] = $classes;
							}
						}
						else
						{
							$plugin->provides[$interface] = is_array($classes) ? $classes : array($classes);
						}
					}
				}
				$pluginsCache[] = $plugin; // FIXME: build a reverse cache too, by provided extension point
			}
			Log::info("Adding plugins to cache");
			Log::debug(var_export($pluginsCache, TRUE));
			Cache::forever('plugins', $pluginsCache);
		} 
		else
		{
			throw new Exception('Missing plugins folder');
		}
	}

	public function getProviders($interface)
	{
		$providers = array();
		Log::debug(sprintf('Getting %s providers from plugin cache', $interface));
		// call the cache to get the providers
		if (Cache::has('plugins')) 
		{
			$plugins = Cache::get('plugins');
			foreach ($plugins as $plugin)
			{
				$provides = $plugin->provides;
				foreach ($provides as $provided => $value)
				{
					if (strcasecmp($provided, $interface) === 0)
					{
						$providers[] = $plugin;
					}
				}
			}
			return $providers;
		}
		Log::warn('Empty plug-in cache');
		return array();
	}

	public function getByPluginId($pluginId)
	{
		// call the cache to get the providers
		if (Cache::has('plugins')) 
		{
			$plugins = Cache::get('plugins');
			foreach ($plugins as $plugin)
			{
				if ($plugin->id == $pluginId)
					return $plugin;
			}
		}
		return NULL;
	}

}
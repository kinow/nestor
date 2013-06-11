<?php

namespace Nestor;

use Monolog\Logger;

interface ThemeManager {
	/**
	 * Scan dir for themes.
	 * @param string $dir
	 * @return multitype:array of Nestor\Theme
	 * @throws \InvalidArgumentException if the dir is null, a directory or not existent
	 */
	public function scan($dir);
	/**
	 * Install a new theme.
	 * @param Nestor\Theme $theme
	 * @throws \InvalidArgumentException if the theme is null
	 * @throws ThemeException if it fails to install the theme
	 */
	public function install($theme);
	/**
	 * Uninstall a previously installed theme.
	 * @param Nestor\Theme $theme
	 * @throws \InvalidArgumentException if the theme is null
	 * @throws ThemeException if it fails to uninstall the theme
	 */
	public function uninstall($theme);
	/**
	 * Check if a theme is installed.
	 * @param Nestor\Theme $theme
	 * @return boolean <code>true</code> if theme is installed, <code>false</code> otherwise of if the theme 
	 * is <code>null</code>.
	 */
	public function is_installed($theme);
	/**
	 * Get list of installed themes.
	 * @return multitype:array of Nestor\Theme
	 */
	public function get_installed_themes();
	
	public function set_active($theme);
	
	public function get_available_themes();
}

final class DefaultThemeManager implements ThemeManager {

	private $logger;
	
	private $themes_cache = array();
	
	/**
	 * 
	 * @param CI_Model $dao
	 */
	public function __construct(&$ci) {
		$this->logger = new Logger('nestor.theme');
		$this->ci = $ci;
		// file cache
		// FIXME: use composer
		$this->ci->load->library('jg_cache', array('cache'), 'cache');
		$this->themes_cache = $this->ci->cache->get('themes_cache');
		if (!$this->themes_cache) {
			$this->themes_cache = array();
			$this->ci->cache->set('themes_cache', $this->themes_cache);
		}
	}
	
	/**
	 * @param string $dir
	 */
	public function scan($dir) {
		if (is_null($dir) || !is_dir($dir))
			throw new \InvalidArgumentException(sprintf('The directory %s does not exist', $dir));
		
		$dir_handle = @opendir($dir);
		if (!$dir_handle) 
			throw new \InvalidArgumentException(sprintf('Failed to open directory %s', $dir));
		
		while ($entry = readdir($dir_handle)) {
			$theme_dir = $dir . $entry;
			if ($entry == '.' || $entry == '..' || is_file($theme_dir))
				continue;
			try {
				$entry = ltrim($entry, '/');
				$entry = rtrim($entry, '/');
				$theme_slug = strtolower($entry);
				if (!array_key_exists($theme_slug, $this->themes_cache)) {
					$theme_array = $this->load_from_theme_dir($theme_dir);
					$theme = new \stdClass();
					foreach ($theme_array as $key => $value) {
						$theme->$key = $value;
					}
					$this->themes_cache[$theme_slug] = $theme;
					$this->logger->addInfo(sprintf('Theme %s loaded from disk successfully into themes cache', $theme->name));
				}
			} catch (\Exception $e) {
				$this->logger->addError(sprintf('Error loading theme from %s: %s', $theme_dir, $e->getMessage()));
			}
			$this->ci->cache->set('themes_cache', $this->themes_cache);
		}
		
		closedir($dir_handle);
	}
	
	/**
	 * 
	 * @param unknown $theme_dir
	 */
	private function load_from_theme_dir($theme_dir) {
		$theme = new \stdClass();
		$plugin_yaml = $theme_dir . '/plugin.yaml';
		if (!file_exists($plugin_yaml)) {
			throw new \InvalidArgumentException('Missing plugin.yaml');
		}
		
		$yaml = \Spyc::YAMLLoad($plugin_yaml);
		
		return $yaml;
	}
	
	/**
	 * 
	 */
	public function get_installed_themes() {
		return $this->ci->themes_model->all();
	}
	
	/**
	 * @param unknown $theme
	 */
	public function install($theme) {
		// TODO: Auto-generated method stub

	}
	
	/**
	 * @param unknown $theme
	 */
	public function is_installed($theme) {
		return ($this->ci->themes_model->get_by_name($theme->get_name()));
	}

	/**
	 * @param unknown $theme
	 */
	public function uninstall($theme) {
		// TODO: Auto-generated method stub
	}
	
	/**
	 * @param unknown $theme
	 */
	public function set_active($theme) {
		$this->ci->themes_model->deactivate_all();
		$this->ci->themes_model->activate($theme);
	}

	/**
	 * 
	 */
	public function get_available_themes() {
		return $this->themes_cache;
	}

}

class ThemeException extends \Exception {
	
	/**
	 * @param string $message
	 * @param string $code
	 * @param string $previous
	 */
	public function __construct($message = null, $code = null, $previous = null) {
		parent::__construct($message, $code, $previous);
	}

}
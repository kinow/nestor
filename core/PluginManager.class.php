<?php

namespace Nestor;

/**
 * Plugin Manager.
 * 
 * @since 0.0.4
 */
final class PluginManager {
	/**
	 * Plugins cache. It is loaded once during application initialization.
	 */	
	public $plugin_cache = array();
	/**
	 * Bus for events triggered in Nestor.
	 */
	public $event_bus = array();
	/**
	 * Add plug-in to cache.
	 * @param Plugin $plugin
	 * @return Plugin
	 */
	public function add_plugin($plugin) {
		if (!isset($this->plugin_cache[$plugin->name])) {
			$this->plugin_cache[$plugin->name] = $plugin;
		}
		return $this->plugin_cache[$plugin->name];
	}
	/**
	 * Remove plug-in from cache.
	 * @param Plugin $plugin, or <code>null</code> if plug-in not loaded yet
	 */
	public function remove_plugin($plugin) {
		if (isset($this->plugin_cache[$plugin->name])) {
			$temp = $this->plugin_cache[$plugin->name];
			unset($this->plugin_cache[$plugin->name]);
			return $temp;
		}
		return null;
	}
	/**
	 * Check if a plug-in is already loaded.
	 * @param Plugin $plugin
	 * @return <code>true</code> if the plug-in is loaded, <code>false</code> otherwise
	 */
	public function is_plugin_loaded($plugin) {
		return (isset($this->plugin_cache[$plugin->name]));
	}
	/**
	 * Install plug-in.
	 * @param Plugin $plugin
	 * @throws PluginManagerException if plugin is already installed or if any error occurs while installing plugin
	 */
	public function install_plugin($plugin) {
		if ($this->is_plugin_installed($plugin)) 
			throw new PluginManagerException("Plug-in $plugin->name is already installed");
		
		if (!$plugin->install())
			throw new PluginManagerException("Failed to install $plugin-name"); 
	}
	/**
	 * Uninstall plug-in.
	 * @param Plugin $plugin
	 */
	public function uninstall_plugin($plugin) {
		
	}
	public function is_plugin_installed($plugin) {
	}
}

class PluginManagerException extends \Exception {}

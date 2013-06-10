<?php

namespace Nestor;

//require_once 'Plugin.class.php';
require_once 'PluginManager.class.php';
require_once 'ThemeManager.class.php';

/**
 * Nestor app.
 * @since 0.1
 */
class Nestor {
	/**
	 * Self instance.
	 * @var \Nestor\Nestor
	 */
	private static $instance;
	/**
	 * Plugin manager.
	 * @var \Nestor\PluginManager
	 */
	public $plugin_manager;
	/**
	 * Default constructor.
	 */
	public function __construct($ci) {
		self::$instance =& $this;
		
		$this->plugin_manager = new PluginManager();
		$ci->load->model('themes_model');
		$this->theme_manager = new DefaultThemeManager($ci);
	}
	/**
	 * Get the plug-in manager.
	 * @return \Nestor\PluginManager
	 */
	public function get_plugin_manager() {
		return $this->plugin_manager;
	}
	/**
	 * Get the theme manager.
	 * @return \Nestor\DefaultThemeManager
	 */
	public function get_theme_manager() {
		return $this->theme_manager;
	}
	/**
	 * Get Nestor app instance.
	 * @return \Nestor\Nestor
	 */
	public static function get_instance() {
		return self::$instance;
	}
	
}

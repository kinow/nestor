<?php

namespace Nestor;

//require_once 'Plugin.class.php';
require_once 'PluginManager.class.php';

class Nestor {
	
	private static $instance;
	
	public $plugin_manager;
	
	public function __construct() {
		self::$instance =& $this;
		
		$this->plugin_manager = new PluginManager();
	}
	
	public function get_plugin_manager() {
		return $this->plugin_manager;
	}
	
	public static function get_instance() {
		return self::$instance;
	}
	
}

$nestor = new Nestor();

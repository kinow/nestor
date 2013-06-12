<?php

namespace Nestor;

/**
 * Nestor plug-in. Provides extension points to Nestor.
 * 
 * @since 0.0.4
 */
abstract class Plugin {
	/**
	 * Plug-in name.
	 */
	public $name = null;
	/**
	 * Plug-in description.
	 */
	public $description = null;
	/**
	 * Version.
	 */
	public $version = 1;
	/**
	 * List of actions this plug-in contains.
	 */
	public $actions = array();
	
	public function install() {
		return true;
	}
	
	public function start() {
		return true;
	}
	
	public function stop() {
		return true;
	}
	
	public function uninstall() {
		return true;
	}
	
}
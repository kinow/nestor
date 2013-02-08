<?php
class Plugin {
	public $name = null;
	public $description = null;
	public $page = null;
	public $version = null;
	public $author = null;
	
	/*
	 * Plug-in lifecycle.
	 */
	
	public function install() {}
	
	public function uninstall() {}
	
	public function start() {}
	
	public function stop() {}
	
	/*
	 * Events.
	 */
	
	public function hooks() {}
	
	public function events() {}
	
}

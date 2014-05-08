<?php

class ConfigurationController extends BaseController {
	
	public function __construct()
	{
		parent::__construct();
		$this->theme->setActive('manage');
	}

	public function getConfigure()
	{
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Manage Nestor', URL::to('/manage'))->
			add('Configure System');

		$settings = Config::get('settings');
		$args = array();
		$args['settings'] = $settings;

		return $this->theme->scope('configuration.index', $args)->render();
	}

}

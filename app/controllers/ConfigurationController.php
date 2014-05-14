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

	public function postConfigure()
	{
		$settings = Config::get('settings');
		foreach ($settings->getConfig() as $name => $value)
		{
			$param = Input::get($name);
			if (isset($param))
			{
				$settings[$name] = $param;
			}
			else
			{
				$settings[$name] = json_encode(NULL);
			}
		}
		return Redirect::to('/configure')
			->with('success', 'Configuration saved!');
	}

}

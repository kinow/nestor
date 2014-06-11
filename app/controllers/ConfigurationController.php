<?php

class ConfigurationController extends BaseController {
	
	public function __construct()
	{
		parent::__construct();
		$this->theme->setActive('manage');
		$this->beforeFilter('@isAuthenticated');
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
		$editorsImplementations = Nestor::getPluginManager()->getProviders("Nestor\\Model\\Editor");
		$editors = array();
		foreach ($editorsImplementations as $editorImplementation)
		{
			$editors[$editorImplementation->id] = $editorImplementation->name;
		}
		$args['editors'] = $editors;

		return $this->theme->scope('configuration.index', $args)->render();
	}

	public function postConfigure()
	{
		Log::info("User is updating configuration...");
		$settings = Config::get('settings');
		foreach ($settings->getConfig() as $name => $value)
		{
			$param = Input::get($name);
			if (isset($param))
			{
				Log::debug("User is setting $name to $param");
				$settings[$name] = $param;
			}
			else
			{
				Log::debug("Missing $name value. Setting it to NULL");
				$settings[$name] = json_encode(NULL);
			}
		}
		return Redirect::to('/configure')
			->with('success', 'Configuration saved!');
	}

}

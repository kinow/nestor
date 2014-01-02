<?php

use \Session;

class BaseController extends Controller {

	/**
	 * Application Theme
	 * @var \Teepluss\Theme\Theme
	 */
	protected $theme;

	protected $currentProject;

	public function __construct()
	{
		$this->theme = Theme::uses('default'); // FIXME: get theme name from config or DB
		$this->theme->setTitle('Nestor QA'); // FIXME: get it from the configs or DB
		// Redirect if Nestor QA is not installed
		if (!isset(Setting::get('nestor')['installed']) || Setting::get('nestor')['installed'] !== true)
		{
			header('Location: /install');
			exit;
		}
		$current_project = Session::get('current_project');
		if ($current_project)
			$current_project = unserialize($current_project);
		$this->currentProject = $current_project;
		$this->theme->set('current_project', $current_project);
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}
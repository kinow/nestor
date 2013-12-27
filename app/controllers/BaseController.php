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
		$this->theme = Theme::uses('default');
		$this->theme->setTitle('Nestor QA');
		// Redirect if Nestor QA is not installed
		if (Setting::get('nestor')['installed'] !== true)
		{
			header('Location: install');
			exit;
		}
		$current_project = Session::get('current_project');
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
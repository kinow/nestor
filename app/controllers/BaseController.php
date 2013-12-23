<?php

use Setting;

class BaseController extends Controller {

	/**
	 * Application Theme
	 * @var \Teepluss\Theme\Theme
	 */
	protected $theme;

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
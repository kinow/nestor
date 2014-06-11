<?php

class ManageController extends BaseController {

	public function __construct()
	{
		parent::__construct();
		$this->theme->setActive('manage');
		$this->beforeFilter('@isAuthenticated');
	}

	public function getIndex()
	{
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Manage Nestor');
		return $this->theme->scope('manage.index')->render();
	}

}
<?php

class ManageController extends BaseController {

	public function __construct()
	{
		parent::__construct();
		$this->theme->setActive('manage');
	}

	public function getIndex()
	{
		return $this->theme->scope('manage.index')->render();
	}

}
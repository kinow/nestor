<?php

class HomeController extends BaseController {

	public function getIndex()
	{
		$this->theme->breadcrumb()->add('Home');
		return $this->theme->scope('home.index')->render();
	}

}
<?php

class HomeController extends BaseController {

	public function getIndex()
	{
		$this->theme->breadcrumb()->add('Home', Request::url());
		return $this->theme->scope('home.index')->render();
	}

}
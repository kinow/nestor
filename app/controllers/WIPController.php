<?php

class WIPController extends BaseController {

	public function getIndex()
	{
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Work In Progress');
		return $this->theme->scope('wip')->render();
	}

}
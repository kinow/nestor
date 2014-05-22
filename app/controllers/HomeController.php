<?php

class HomeController extends BaseController {

	public function getIndex()
	{
		$event = Event::fire('home', array('data' => 123));
		Log::debug(var_export($event, TRUE));
		$this->theme->breadcrumb()->add('Home');
		$args = array();
		if (class_exists('TESTE'))
			$args['TESTE'] = App::make('TESTE');
		return $this->theme->scope('home.index')->render();
	}

}
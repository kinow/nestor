<?php

use Kinow\WysiwygEditor\WysiwygEditorServiceProvider;

class HomeController extends BaseController {

	public function getIndex()
	{

		//App::register('Kinow\WysiwygEditor\WysiwygEditorServiceProvider');
		$event = Event::fire('home', array('data' => 123));
		Log::debug(var_export($event, TRUE));
		$this->theme->breadcrumb()->add('Home');
		$args = array();
		try 
		{
			$args['TESTE'] = App::make('Editor');
		}
		catch (Exception $e)
		{
			$args['TESTE'] = $e->getMessage();
		}

		return $this->theme->scope('home.index', $args)->render();
	}

}
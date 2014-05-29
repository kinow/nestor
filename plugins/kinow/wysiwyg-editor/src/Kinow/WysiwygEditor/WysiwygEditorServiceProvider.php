<?php namespace Kinow\WysiwygEditor;

use Exception;
use App;
use Illuminate\Support\ServiceProvider;

class WysiwygEditorServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('kinow/wysiwyg-editor');
		\View::addNamespace('kinow/wysiwyg-editor', app_path() . '/views/packages/kinow/wysiwyg-editor/');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		
	}

}

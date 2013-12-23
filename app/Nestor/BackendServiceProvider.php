<?php namespace Nestor;

use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('Nestor\Repositories\UserRepositoryInterface', 'Nestor\Repositories\DbUserRepository');

		$this->app->bind('Nestor', function()
		{
			return new \Nestor\Facades\Nestor();
		});
	}

}
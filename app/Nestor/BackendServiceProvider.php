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
		$this->app->singleton('Nestor\Repositories\UserRepository', 'Nestor\Repositories\DbUserRepository');
		$this->app->singleton('Nestor\Repositories\ProjectRepository', 'Nestor\Repositories\DbProjectRepository');
		$this->app->singleton('Nestor\Repositories\ProjectStatusRepository', 'Nestor\Repositories\DbProjectStatusRepository');
		$this->app->singleton('Nestor\Repositories\ExecutionTypeRepository', 'Nestor\Repositories\DbExecutionTypeRepository');
		$this->app->singleton('Nestor\Repositories\NavigationTreeRepository', 'Nestor\Repositories\DbNavigationTreeRepository');

		$this->app->bind('Nestor', function()
		{
			return new \Nestor\Facades\Nestor();
		});
	}

}
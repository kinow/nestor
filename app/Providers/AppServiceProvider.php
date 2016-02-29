<?php

namespace Nestor\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        app('Dingo\Api\Auth\Auth')->extend('basic', function ($app) {
            return new \Dingo\Api\Auth\Provider\Basic($app['auth'], 'email');
        });

        // bind repositories
        $this->app->bind('Nestor\Repositories\UsersRepository', 'Nestor\Repositories\UsersRepositoryEloquent');
        
        $this->app->bind('Nestor\Repositories\ProjectStatusesRepository', 'Nestor\Repositories\ProjectsStatusesRepositoryEloquent');
        $this->app->bind('Nestor\Repositories\ProjectsRepository', 'Nestor\Repositories\ProjectsRepositoryEloquent');
        
        $this->app->bind('Nestor\Repositories\ExecutionStatusesRepository', 'Nestor\Repositories\ExecutionStatusesRepositoryEloquent');
        $this->app->bind('Nestor\Repositories\ExecutionTypesRepository', 'Nestor\Repositories\ExecutionTypesRepositoryEloquent');
        $this->app->bind('Nestor\Repositories\NavigationTreeNodeTypesRepository', 'Nestor\Repositories\NavigationTreeNodeTypesRepositoryEloquent');
        $this->app->bind('Nestor\Repositories\NavigationTreeRepository', 'Nestor\Repositories\NavigationTreeRepositoryEloquent');
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }
}

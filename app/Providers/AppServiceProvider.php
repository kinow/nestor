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
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }
}

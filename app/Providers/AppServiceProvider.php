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
        $this->app->bind('Nestor\Repositories\UserRepository', 'Nestor\Repositories\UserRepositoryEloquent');
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }
}

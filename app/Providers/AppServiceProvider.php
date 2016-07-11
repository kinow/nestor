<?php
/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 Bruno P. Kinoshita, Peter Florijn
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

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
            return new \Dingo\Api\Auth\Provider\Basic($app ['auth'], 'email');
        });
        
        // bind repositories
        $this->app->bind('Nestor\Repositories\UsersRepository', 'Nestor\Repositories\UsersRepositoryEloquent');
        
        $this->app->bind('Nestor\Repositories\ProjectStatusesRepository', 'Nestor\Repositories\ProjectStatusesRepositoryEloquent');
        $this->app->bind('Nestor\Repositories\ProjectsRepository', 'Nestor\Repositories\ProjectsRepositoryEloquent');
        
        $this->app->bind('Nestor\Repositories\ExecutionStatusesRepository', 'Nestor\Repositories\ExecutionStatusesRepositoryEloquent');
        $this->app->bind('Nestor\Repositories\ExecutionTypesRepository', 'Nestor\Repositories\ExecutionTypesRepositoryEloquent');
        $this->app->bind('Nestor\Repositories\NavigationTreeNodeTypesRepository', 'Nestor\Repositories\NavigationTreeNodeTypesRepositoryEloquent');
        $this->app->bind('Nestor\Repositories\NavigationTreeRepository', 'Nestor\Repositories\NavigationTreeRepositoryEloquent');
        $this->app->bind('Nestor\Repositories\TestSuitesRepository', 'Nestor\Repositories\TestSuitesRepositoryEloquent');
        $this->app->bind('Nestor\Repositories\TestCasesRepository', 'Nestor\Repositories\TestCasesRepositoryEloquent');
        $this->app->bind('Nestor\Repositories\TestPlansRepository', 'Nestor\Repositories\TestPlansRepositoryEloquent');
        $this->app->bind('Nestor\Repositories\TestRunsRepository', 'Nestor\Repositories\TestRunsRepositoryEloquent');
        $this->app->bind('Nestor\Repositories\ExecutionsRepository', 'Nestor\Repositories\ExecutionsRepositoryEloquent');
    }
    
    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }
}

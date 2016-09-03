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

/*
 * |--------------------------------------------------------------------------
 * | Application Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register all of the routes for an application.
 * | It's a breeze. Simply tell Laravel the URIs it should respond to
 * | and give it the controller to call when that URI is requested.
 * |
 */
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes...
// Route::get('auth/login', 'Auth\AuthController@getLogin');
// Route::post('auth/login', 'Auth\AuthController@postLogin');
// Route::get('auth/logout', 'Auth\AuthController@getLogout');
//
// // Registration routes...
// Route::get('auth/register', 'Auth\AuthController@getRegister');
// Route::post('auth/register', 'Auth\AuthController@postRegister');

app('Dingo\Api\Exception\Handler')->register(function (Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException $exception) {
    return Response::make([
            'error' => 'Hey, what do you think you are doing!?'
    ], 401);
});

app('Dingo\Api\Exception\Handler')->register(function (Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception) {
    return Response::make([
            'error' => 'Not found'
    ], 404);
});

app('Dingo\Api\Exception\Handler')->register(function (Dingo\Api\Exception\StoreResourceFailedException $exception) {
    return Response::make([
            'error' => 'Failed to save user: ' .$exception->getMessage()
    ], 422);
});

app('Dingo\Api\Exception\Handler')->register(function (Illuminate\Http\Exception\HttpResponseException $exception) {
    return Response::make([
            'error' => 'Validation error: ' .$exception->getMessage()
    ], 422);
});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    // auth
    $api->get('auth/', 'Nestor\Http\Controllers\UsersController@doCheckLogin');
    $api->post('auth/signup', 'Nestor\Http\Controllers\UsersController@doSignUp');
    $api->post('auth/login', 'Nestor\Http\Controllers\UsersController@doLogin');
    {
        $api->get('auth/logout', 'Nestor\Http\Controllers\UsersController@doLogout');
        $api->post('auth/logout', 'Nestor\Http\Controllers\UsersController@doLogout');
    }

    // core
    $api->get('executiontypes', 'Nestor\Http\Controllers\ExecutionTypesController@index');
    $api->get('executionstatuses', 'Nestor\Http\Controllers\ExecutionStatusesController@index');
    $api->post('users', 'Nestor\Http\Controllers\UsersController@update');
    
    // projects
    $api->get('projects', 'Nestor\Http\Controllers\ProjectsController@index');
    $api->post('projects', 'Nestor\Http\Controllers\ProjectsController@store');
    $api->get('projects/{id}', 'Nestor\Http\Controllers\ProjectsController@show');
    $api->put('projects/{id}', 'Nestor\Http\Controllers\ProjectsController@update');
    $api->delete('projects/{id}', 'Nestor\Http\Controllers\ProjectsController@destroy');
    $api->get('projects/{projectId}/position', 'Nestor\Http\Controllers\ProjectsController@position');
    
    // TODO: example with auth, to use in the future when locking the API
    // test suites
    // $api->get('projects/{projectId}/testsuites/{testSuiteId}', [
    // 'middleware' => 'api.auth'
    // ], 'Nestor\Http\Controllers\TestSuitesController@show');
    $api->get('projects/{projectId}/testsuites/{testSuiteId}', 'Nestor\Http\Controllers\TestSuitesController@show');
    $api->post('projects/{projectId}/testsuites', 'Nestor\Http\Controllers\TestSuitesController@store');
    $api->put('projects/{projectId}/testsuites/{testSuiteId}', 'Nestor\Http\Controllers\TestSuitesController@update');
    $api->delete('projects/{projectId}/testsuites/{testSuiteId}', 'Nestor\Http\Controllers\TestSuitesController@destroy');

    // test cases
    $api->get('projects/{projectId}/testsuites/{testsuiteId}/testcases/{testcaseId}', 'Nestor\Http\Controllers\TestCasesController@show');
    $api->post('projects/{projectId}/testsuites/{testsuiteId}/testcases', 'Nestor\Http\Controllers\TestCasesController@store');
    $api->put('projects/{projectId}/testsuites/{testsuiteId}/testcases/{testcaseId}', 'Nestor\Http\Controllers\TestCasesController@update');
    $api->delete('projects/{projectId}/testsuites/{testsuiteId}/testcases/{testcaseId}', 'Nestor\Http\Controllers\TestCasesController@destroy');
    
    // navigation tree
    $api->get('navigationtree', 'Nestor\Http\Controllers\NavigationTreeController@index');
    $api->get('navigationtree/{id}', 'Nestor\Http\Controllers\NavigationTreeController@show');
    $api->post('navigationtree/move', 'Nestor\Http\Controllers\NavigationTreeController@move');

    // test plans
    $api->get('testplans', 'Nestor\Http\Controllers\TestPlansController@index');
    $api->post('testplans', 'Nestor\Http\Controllers\TestPlansController@store');
    $api->get('testplans/{id}', 'Nestor\Http\Controllers\TestPlansController@show');
    $api->put('testplans/{id}', 'Nestor\Http\Controllers\TestPlansController@update');
    $api->delete('testplans/{id}', 'Nestor\Http\Controllers\TestPlansController@destroy');
    $api->post('testplans/{id}/store', 'Nestor\Http\Controllers\TestPlansController@storeTestCases');

    // test runs
    $api->get('testplans/{testPlanId}/testruns', 'Nestor\Http\Controllers\TestRunsController@index');
    $api->post('testplans/{testPlanId}/testruns', 'Nestor\Http\Controllers\TestRunsController@store');
    $api->get('testplans/{testPlanId}/testruns/{id}', 'Nestor\Http\Controllers\TestRunsController@show');
    $api->put('testplans/{testPlanId}/testruns/{id}', 'Nestor\Http\Controllers\TestRunsController@update');
    $api->delete('testplans/{testPlanId}/testruns/{id}', 'Nestor\Http\Controllers\TestRunsController@destroy');

    // executions
    $api->get('testplans/{testPlanId}/testruns/{testRunId}/testsuites/{testsuiteId}/testcases/{testcaseId}/executions', 'Nestor\Http\Controllers\ExecutionsController@showTestCase');
    $api->post('testplans/{testPlanId}/testruns/{testRunId}/testsuites/{testsuiteId}/testcases/{testcaseId}/executions', 'Nestor\Http\Controllers\ExecutionsController@executeTestCase');
    $api->get('executions', 'Nestor\Http\Controllers\ExecutionsController@index');
});

// Display all SQL executed in Eloquent
// Event::listen('illuminate.query', function ($query) {
//     Log::debug($query);
// });

if (Config::get('database.log', false)) {
    Event::listen('illuminate.query', function ($query, $bindings, $time, $name) {
        $data = compact('bindings', 'time', 'name');

        // Format binding data for sql insertion
        foreach ($bindings as $i => $binding) {
            if ($binding instanceof \DateTime) {
                $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
            } else if (is_string($binding)) {
                $bindings[$i] = "'$binding'";
            }
        }

        // Insert bindings into query
        $query = str_replace(array('%', '?'), array('%%', '%s'), $query);
        $query = vsprintf($query, $bindings);

        Log::debug($query, $data);
    });
}

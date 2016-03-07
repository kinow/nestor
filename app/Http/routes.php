<?php

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
    
    // projects
    $api->get('projects', 'Nestor\Http\Controllers\ProjectsController@index');
    $api->post('projects', 'Nestor\Http\Controllers\ProjectsController@store');
    $api->get('projects/{id}', 'Nestor\Http\Controllers\ProjectsController@show');
    $api->put('projects/{id}', 'Nestor\Http\Controllers\ProjectsController@update');
    $api->delete('projects/{id}', 'Nestor\Http\Controllers\ProjectsController@destroy');
    
    // test suites
//     $api->get('projects/{projectId}/testsuites/{testSuiteId}', [ 
//             'middleware' => 'api.auth' 
//     ], 'Nestor\Http\Controllers\TestSuitesController@show');
    $api->get('projects/{projectId}/testsuites/{testSuiteId}', 'Nestor\Http\Controllers\TestSuitesController@show');
    
    // navigation tree
    $api->get('navigationtree', 'Nestor\Http\Controllers\NavigationTreeController@index');
    // $api->post('navigationtree', 'Nestor\Http\Controllers\NavigationTreeController@store');
    $api->get('navigationtree/{id}', 'Nestor\Http\Controllers\NavigationTreeController@show');
    // $api->put('navigationtree/{id}', 'Nestor\Http\Controllers\NavigationTreeController@update');
    // $api->delete('navigationtree/{id}', 'Nestor\Http\Controllers\NavigationTreeController@destroy');
});

// Display all SQL executed in Eloquent
Event::listen('illuminate.query', function ($query) {
    Log::debug($query);
});

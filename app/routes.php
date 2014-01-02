<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// Main app
Route::get('/', 'HomeController@getIndex');
Route::controller('install', 'InstallController');
Route::get('/manage', 'ManageController@getIndex');

// Projects
Route::get('projects/position', 'ProjectsController@position');
Route::resource('projects', 'ProjectsController');

// Test Suites
Route::resource('testsuites', 'TestSuitesController');

// Test Cases
Route::resource('testcases', 'TestCasesController');

// Specification
Route::controller('/specification', 'SpecificationController');
Route::controller('/specification/nodes', 'SpecificationController');

// Test Plans
Route::resource('planning', 'TestPlansController');
Route::resource('testplans', 'TestPlansController');
Route::get('testplans/{id}/addTestCases', 'TestPlansController@addTestCases');
Route::post('testplans/{id}/addTestCases', 'TestPlansController@storeTestCases');

// WIP
Route::controller('requirements', 'WIPController');
Route::controller('execution', 'WIPController');
Route::controller('reports', 'WIPController');
Route::controller('configure', 'WIPController');
Route::controller('themeManager', 'WIPController');
Route::controller('pluginManager', 'WIPController');

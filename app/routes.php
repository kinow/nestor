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
Route::get('/configure', 'ConfigurationController@getConfigure');

// Projects
Route::get('projects/position', 'ProjectsController@position');
Route::resource('projects', 'ProjectsController');

// Test Suites
Route::resource('testsuites', 'TestSuitesController');

// Test Cases
Route::resource('testcases', 'TestCasesController');

// Specification
Route::post('specification/moveNode', 'SpecificationController@postMoveNode');
Route::controller('/specification', 'SpecificationController');

// Test Plans
Route::resource('planning', 'TestPlansController');
Route::resource('testplans', 'TestPlansController');
Route::get('planning/{id}/addTestCases', 'TestPlansController@addTestCases');
Route::post('planning/{id}/addTestCases', 'TestPlansController@storeTestCases');

// Test Execution
Route::get('execution/testruns/{test_run_id}/run/testcase/{test_case_id}', 'TestRunsController@runTestCase');
Route::post('execution/testruns/{test_run_id}/run/testcase/{test_case_id}', 'TestRunsController@runTestCasePost');
Route::get('execution/testruns/{id}/run', 'TestRunsController@runGet');
Route::resource('execution/testruns', 'TestRunsController');
Route::resource('execution', 'ExecutionsController');

// WIP
Route::controller('requirements', 'WIPController');
Route::controller('reports', 'WIPController');
Route::controller('themeManager', 'WIPController');
Route::controller('pluginManager', 'WIPController');


if (Config::get('database.log', false))
{           
    Event::listen('illuminate.query', function($query, $bindings, $time, $name)
    {
        $data = compact('bindings', 'time', 'name');

        // Format binding data for sql insertion
        foreach ($bindings as $i => $binding)
        {   
            if ($binding instanceof \DateTime)
            {   
                $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
            }
            else if (is_string($binding))
            {   
                $bindings[$i] = "'$binding'";
            }   
        }       

        // Insert bindings into query
        $query = str_replace(array('%', '?'), array('%%', '%s'), $query);
        $query = vsprintf($query, $bindings); 

        Log::info($query, $data);
    });
}
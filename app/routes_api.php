<?php

/* API Routes */

Route::group(array('prefix' => 'api/v1'/*, 'before' => 'auth.basic'*/), function()
{
    Route::resource('projects', 'Nestor\Controllers\ProjectsController');
    // project's test cases
    Route::get('projects/{projectId}/testsuites', 'Nestor\Controllers\TestSuitesController@getTestSuitesByProject');
    // position project
    Route::post('projects/position/{projectId}', 'Nestor\Controllers\ProjectsController@position');
    Route::resource('nodes', 'Nestor\Controllers\NodesController');
});
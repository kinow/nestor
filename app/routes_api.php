<?php

/* API Routes */

Route::group(array('prefix' => 'api/v1'/*, 'before' => 'auth.basic'*/), function()
{
    Route::resource('projects', 'Nestor\Controllers\ProjectsController');
    // position project
    Route::post('projects/position/{projectId}', 'Nestor\Controllers\ProjectsController@position');
    Route::resource('nodes', 'Nestor\Controllers\NodesController');
});
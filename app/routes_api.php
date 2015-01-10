<?php

/* API Routes */

Route::group(array('prefix' => 'api/v1'/*, 'before' => 'auth.basic'*/), function()
{
    Route::resource('projects', 'Nestor\Controllers\ProjectsController');
    Route::resource('nodes', 'Nestor\Controllers\NodesController');
});
<?php

/* API Routes */

Route::group(array('prefix' => 'api/v1'/*, 'before' => 'auth.basic'*/), function()
{
	/*
	 * Projects
	 */
    Route::resource('projects', 'Nestor\Controllers\ProjectsController');
    // project's test cases
    Route::get('projects/{projectId}/testsuites', 'Nestor\Controllers\TestSuitesController@getTestSuitesByProject');
    // position project
    Route::post('projects/position/{projectId}', 'Nestor\Controllers\ProjectsController@position');
    Route::resource('nodes', 'Nestor\Controllers\NodesController');

    /*
	 * Test suites
	 */
    Route::resource('testsuites', 'Nestor\Controllers\TestSuitesController');

    /*
     * Test cases
     */
    Route::resource('testcases', 'Nestor\Controllers\TestCasesController');

    /*
     * Execution types.
     */
    Route::resource('executiontypes', 'Nestor\Controllers\ExecutionTypesController');

    /*
     * Execution statuses.
     */
    Route::resource('executionstatuses', 'Nestor\Controllers\ExecutionStatusesController');

    /*
     * Specification
     */
    Route::post('nodes/move', 'Nestor\Controllers\NodesController@move');
});
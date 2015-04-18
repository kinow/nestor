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

    /*
     * Test plans
     */
    Route::resource('testplans', 'Nestor\Controllers\TestPlansController');
    Route::post('testplans/{testPlanId}/testcases/{testCaseVersionId}', 'Nestor\Controllers\TestPlansController@addTestCase');
    Route::delete('testplans/{testPlanId}/testcases/{testCaseVersionId}', 'Nestor\Controllers\TestPlansController@removeTestCase');
    Route::get('testplans/{testPlanId}/testruns', 'Nestor\Controllers\TestPlansController@getTestRunsByTestPlan');
    Route::get('projects/{projectId}/testplans', 'Nestor\Controllers\TestPlansController@indexForProject');

    /*
     * Execution
     */
    Route::resource('executions', 'Nestor\Controllers\ExecutionsController');

    Route::resource('execution/testruns', 'Nestor\Controllers\TestRunsController');
    Route::get('execution/testruns/{testRunId}/executions/{testCaseVersionId}', 'Nestor\Controllers\TestRunsController@getExecutionsForTestCaseVersion');
    Route::post('execution/testruns/{testRunId}/executions/{testCaseId}', 'Nestor\Controllers\TestRunsController@executeTestCase');
    Route::get('execution/testruns/{testRunId}/testsuites', 'Nestor\Controllers\TestRunsController@getTestSuites');
});
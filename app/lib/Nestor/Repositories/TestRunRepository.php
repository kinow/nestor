<?php namespace Nestor\Repositories;

interface TestRunRepository {

	public function isNameAvailable($id, $testPlanId, $name);

	/**
	 * Retrieves test suites from a given test run, by its ID.
	 * @param int $testRunId
	 * @return TestSuite
	 */
	public function getTestSuites($testRunId);

	/**
	 * Retrieves test cases from a given test run, by its ID. The object returned is a merge
	 * of the test case and its version.
	 * @param int $testRunId
	 * @return mixed
	 */
	public function getTestCases($testRunId);

}
<?php namespace Nestor\Repositories;

interface TestCaseStepRepository {

	/**
	 * Get a TestCaseStep by their test case version id.
	 *
	 * @param  int   $testCaseVersionId
	 * @return TestCaseStep
	 */
	public function findByTestCaseVersion($testCaseVersionId);

}
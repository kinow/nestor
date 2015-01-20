<?php namespace Nestor\Repositories;

interface TestCaseRepository {

	public function getVersion($version);

	/**
	 * Looks for existing test cases with a given name, within a test suite.
	 * 
	 * @param int      $id 
	 * @param int      $test_suite_id
	 * @param string   $name
	 * @return boolean
	 */
	public function isNameAvailable($id, $test_suite_id, $name);

	public function addLabels($id, $labels);

	public function createNewTestCase(array $testCaseArray, array $testCaseVersionArray);

	public function findTestCase($id);

}
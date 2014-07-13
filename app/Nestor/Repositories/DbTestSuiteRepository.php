<?php namespace Nestor\Repositories;

use Auth, Hash, Validator;
use TestSuite;
use Label;

class DbTestSuiteRepository implements TestSuiteRepository {

	/**
	 * Get all of the test suites.
	 *
	 * @return array
	 */
	public function all()
	{
		return TestSuite::all();
	}

	/**
	 * Get a TestSuite by their primary key.
	 *
	 * @param  int   $id
	 * @return TestSuite
	 */
	public function find($id)
	{
		return TestSuite::findOrFail($id);
	}

	/**
	 * Create a test suite
	 *
	 * @param  int     $project_id
	 * @param  string  $name
	 * @param  string  $description
	 * @return TestSuite
	 */
	public function create($project_id, $name, $description)
	{
		return TestSuite::create(compact('project_id', 'name', 'description'));
	}

	/**
	 * Update a test suite
	 *
	 * @param  int     $id
	 * @param  int     $project_id
	 * @param  string  $name
	 * @param  string  $description
	 * @return TestSuite
	 */
	public function update($id, $project_id, $name, $description)
	{
		$test_suite = $this->find($id);

		$test_suite->fill(compact('project_id', 'name', 'description'))->save();

		return $test_suite;
	}

	/**
	 * Delete a test suite
	 * @param int $id
	 */
	public function delete($id)
	{
		return TestSuite::where('id', $id)->delete();
	}

	public function copy($oldName, $newName, $testcaseRepository)
	{
		$testsuite = TestSuite::where(
			new \Illuminate\Database\Query\Expression("lower(test_suites.name)"), '=', strtolower($oldName))
			->firstOrFail();
		$labels = $testsuite->labels()->get();
		$newTestsuite = $this->create($testsuite->project_id, $newName, $testsuite->description);
		foreach ($labels as $label)
		{
			$newTestsuite->labels()->attach($label->id);
		}
		return array($testsuite, $newTestsuite);
	}

}

<?php namespace Nestor\Repositories;

use Auth, Hash, Validator;
use \ExecutionStatus;

class DbExecutionStatusRepository implements ExecutionStatusRepository {

	/**
	 * Get all of the test executions.
	 *
	 * @return array
	 */
	public function all()
	{
		return ExecutionStatus::all();
	}

	/**
	 * Get a ExecutionStatus by their primary key.
	 *
	 * @param  int   $id
	 * @return ExecutionStatus
	 */
	public function find($id)
	{
		return ExecutionStatus::findOrFail($id);
	}

	/**
	 * Create a test execution
	 *
	 * @param  string  $name
	 * @param  string  $description
	 * @return ExecutionStatus
	 */
	public function create($name, $description)
	{
		return ExecutionStatus::create(compact('name', 'description'));
	}

	/**
	 * Update a test execution
	 *
	 * @param  int     $id
	 * @param  string  $name
	 * @param  string  $description
	 * @return ExecutionStatus
	 */
	public function update($id, $name, $description)
	{
		$test_execution = $this->find($id);

		$test_execution->fill(compact('name', 'description'))->save();

		return $test_execution;
	}

	/**
	 * Delete a test execution
	 *
	 * @param int $id
	 */
	public function delete($id)
	{
		return ExecutionStatus::where('id', $id)->delete();
	}

}

<?php namespace Nestor\Repositories;

interface ExecutionStatusRepository {

	/**
	 * Get all execution statuss
	 *
	 * @return ExecutionStatus
	 */
	public function all();

	/**
	 * Get a ExecutionStatus by their primary key.
	 *
	 * @param  int   $id
	 * @return ExecutionStatus
	 */
	public function find($id);

	/**
	 * Create a execution status
	 *
	 * @param  string  $name
	 * @param  string  $description
	 * @return ExecutionStatus
	 */
	public function create($name, $description);

	/**
	 * Update a execution status
	 *
	 * @param  int     $id
	 * @param  string  $name
	 * @param  string  $description
	 * @return ExecutionStatus
	 */
	public function update($id, $name, $description);

	/**
	 * Delete a execution status
	 *
	 * @param int $id
	 */
	public function delete($id);

}
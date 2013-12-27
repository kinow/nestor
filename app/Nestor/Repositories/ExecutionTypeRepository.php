<?php namespace Nestor\Repositories;

interface ExecutionTypeRepository {

	/**
	 * Get all execution types
	 *
	 * @return ExecutionType
	 */
	public function all();

	/**
	 * Get a ExecutionType by their primary key.
	 *
	 * @param  int   $id
	 * @return ExecutionType
	 */
	public function find($id);

	/**
	 * Create a execution type
	 *
	 * @param  string  $name
	 * @param  string  $description
	 * @return ExecutionType
	 */
	public function create($name, $description);

	/**
	 * Update a execution type
	 *
	 * @param  int  $id
	 * @param  string  $name
	 * @param  string  $description
	 * @return ProjectStatus
	 */
	public function update($id, $name, $description);

	/**
	 * Delete a execution type
	 *
	 * @param int $id
	 */
	public function delete($id);

}
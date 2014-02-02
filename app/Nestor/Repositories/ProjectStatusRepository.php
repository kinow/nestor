<?php namespace Nestor\Repositories;

interface ProjectStatusRepository {

	/**
	 * Get all project statuses
	 *
	 * @return ProjectStatus
	 */
	public function all();

	/**
	 * Get a ProjectStatus by their primary key.
	 *
	 * @param  int   $id
	 * @return ProjectStatus
	 */
	public function find($id);

	/**
	 * Create a project status
	 *
	 * @param  string  $name
	 * @param  string  $description
	 * @return Project
	 */
	public function create($name, $description);

	/**
	 * Update a project status
	 *
	 * @param  int  $id
	 * @param  string  $name
	 * @param  string  $description
	 * @return ProjectStatus
	 */
	public function update($id, $name, $description);

	/**
	 * Delete a project status
	 *
	 * @param int $id
	 */
	public function delete($id);

}
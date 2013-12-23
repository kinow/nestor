<?php namespace Nestor\Repositories;

interface ProjectRepository {

	/**
	 * Get all projects
	 *
	 * @return Project
	 */
	public function all();

	/**
	 * Get a Project by their primary key.
	 *
	 * @param  int   $id
	 * @return Project
	 */
	public function find($id);

	/**
	 * Create a project
	 *
	 * @param  string  $name
	 * @param  string  $description
	 * @param  int  $project_statuses_id
	 * @return Project
	 */
	public function create($name, $description, $project_statuses_id);

	/**
	 * Update a project
	 *
	 * @param  int  $id
	 * @param  string  $name
	 * @param  string  $description
	 * @param  int  $project_statuses_id
	 * @return Project
	 */
	public function update($id, $name, $description, $project_statuses_id);

	/**
	 * Validate that the given project is valid for creation.
	 *
	 * @param  string  $name
	 * @param  string  $description
	 * @param  int  $project_statuses_id
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForCreation($name, $description, $project_statuses_id);

	/**
	 * Validate that the given project is valid for updating.
	 *
	 * @param  int  $id
	 * @param  string  $name
	 * @param  string  $description
	 * @param  int  $project_statuses_id
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForUpdate($id, $name, $description, $project_statuses_id);

	/**
	 * Delete a project
	 *
	 * @param int $id
	 */
	public function delete($id);

}
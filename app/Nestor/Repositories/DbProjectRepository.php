<?php namespace Nestor\Repositories;

use Auth, Hash, Validator;
use \Project;

class DbProjectRepository implements ProjectRepository {

	/**
	 * Get all of the projects.
	 *
	 * @return array
	 */
	public function all()
	{
		return Project::all();
	}

	/**
	 * Get a Project by their primary key.
	 *
	 * @param  int   $id
	 * @return Project
	 */
	public function find($id)
	{
		return Project::findOrFail($id);
	}

	/**
	 * Create a project
	 *
	 * @param  string  $name
	 * @param  string  $description
	 * @param  int  $project_statuses_id
	 * @return Project
	 */
	public function create($name, $description, $project_statuses_id)
	{
		return Project::create(compact('name', 'description', 'project_statuses_id'));
	}

	/**
	 * Update a project
	 *
	 * @param  int  $id
	 * @param  string  $name
	 * @param  string  $description
	 * @param  int  $project_statuses_id
	 * @return Project
	 */
	public function update($id, $name, $description, $project_statuses_id)
	{
		$project = $this->find($id);

		$project->fill(compact('name', 'description', 'project_statuses_id'))->save();

		return $project;
	}

	/**
	 * Validate that the given project is valid for creation.
	 *
	 * @param  string  $name
	 * @param  string  $description
	 * @param  int  $project_statuses_id
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForCreation($name, $description, $project_statuses_id)
	{
		return $this->validateProject($name, $description, $project_statuses_id);
	}

	/**
	 * Validate that the given project is valid for updating.
	 *
	 * @param  int  $id
	 * @param  string  $name
	 * @param  string  $description
	 * @param  int  $project_statuses_id
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForUpdate($id, $name, $description, $project_statuses_id)
	{
		return $this->validateProject($name, $description, $project_statuses_id);
	}

	/**
	 * Validate the given project data.
	 *
	 * @param  int  $id
	 * @param  string  $name
	 * @param  string  $description
	 * @param  int  $project_statuses_id
	 *
	 * @return \Illuminate\Support\MessageBag
	 */
	protected function validateProject($name, $description, $project_statuses_id, $id = null)
	{
		$rules = array(
			'name' => 'required|max:255',
			'description'  => 'required|max:255',
			'project_statuses_id'      => 'required|integer|min:1',
		);

		$validator = Validator::make(
			compact('name', 'description', 'project_statuses_id'), $rules
		);

		$validator->passes();

		return $validator->errors();
	}

	/**
	 * Delete a project
	 * @param int $id
	 */
	public function delete($id)
	{
		return Project::where('id', $id)->delete();
	}

}

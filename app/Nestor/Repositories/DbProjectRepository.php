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
		return Project::where('project_statuses_id', '<>', 2)->get();
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
	 * Delete a project
	 * @param int $id
	 */
	public function delete($id)
	{
		return Project::where('id', $id)->delete();
	}

	/** 
	 * Deactivates a project by changing its status.
	 * 
	 * @param int $id
	 * @return Project
	 */
	public function deactivate($id)
	{
		$project = $this->find($id);

		$project->fill(array('project_statuses_id' => 2))->save(); // TODO: use constants

		return $project;
	}

}

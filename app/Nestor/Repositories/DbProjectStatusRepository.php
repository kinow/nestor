<?php namespace Nestor\Repositories;

use Auth, Hash, Validator;
use \ProjectStatus;

class DbProjectStatusRepository implements ProjectStatusRepository {

	/**
	 * Get all of the project statuses.
	 *
	 * @return array
	 */
	public function all()
	{
		return ProjectStatus::all();
	}

	/**
	 * Get a ProjectStatus by their primary key.
	 *
	 * @param  int   $id
	 * @return ProjectStatus
	 */
	public function find($id)
	{
		return ProjectStatus::findOrFail($id);
	}

	/**
	 * Create a project status
	 *
	 * @param  string  $name
	 * @param  string  $description
	 * @return ProjectStatus
	 */
	public function create($name, $description)
	{
		return ProjectStatus::create(compact('name', 'description'));
	}

	/**
	 * Update a project status
	 *
	 * @param  int  $id
	 * @param  string  $name
	 * @param  string  $description
	 * @return ProjectStatus
	 */
	public function update($id, $name, $description)
	{
		$project_status = $this->find($id);

		$project_status->fill(compact('name', 'description'))->save();

		return $project_status;
	}

	/**
	 * Delete a project status
	 * @param int $id
	 */
	public function delete($id)
	{
		return ProjectStatus::where('id', $id)->delete();
	}

}

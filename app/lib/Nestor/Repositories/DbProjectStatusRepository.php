<?php namespace Nestor\Repositories;

use Auth, Hash, Validator;
use Nestor\Model\ProjectStatus;

class DbProjectStatusRepository implements ProjectStatusRepository {

	public function all()
	{
		return ProjectStatus::all();
	}

	public function find($id)
	{
		return ProjectStatus::findOrFail($id);
	}

	public function create($id, $name, $description)
	{
		return ProjectStatus::create(compact('id', 'name', 'description'));
	}

	public function update($id, $name, $description)
	{
		$project_status = $this->find($id);

		$project_status->fill(compact('name', 'description'))->save();

		return $project_status;
	}

	public function delete($id)
	{
		return ProjectStatus::where('id', $id)->delete();
	}

}

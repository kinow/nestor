<?php namespace Nestor\Repositories;

use ReportType;

class DbReportTypeRepository implements ReportTypeRepository {

	public function all()
	{
		return ReportType::all();
	}

	public function find($id)
	{
		return ReportType::findOrFail($id);
	}

	public function create($id, $name, $description)
	{
		return ReportType::create(compact('id', 'name', 'description'));
	}

	public function update($id, $name, $description)
	{
		$entity = $this->find($id);
		$entity->fill(compact('name', 'description'))->save();
		return $entity;
	}

	public function delete($id)
	{
		return ReportType::where('id', $id)->delete();
	}

}

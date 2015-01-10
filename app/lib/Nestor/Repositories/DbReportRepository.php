<?php namespace Nestor\Repositories;

use Report;

class DbReportRepository implements ReportRepository {

	public function all()
	{
		return Report::all();
	}

	public function find($id)
	{
		return Report::findOrFail($id);
	}

	public function create($report_type_id, $name, $description)
	{
		return Report::create(compact('report_type_id', 'name', 'description'));
	}

	public function update($id, $report_type_id, $name, $description)
	{
		$entity = $this->find($id);
		$entity->fill(compact('report_type_id', 'name', 'description'))->save();
		return $entity;
	}

	public function delete($id)
	{
		return Report::where('id', $id)->delete();
	}

}

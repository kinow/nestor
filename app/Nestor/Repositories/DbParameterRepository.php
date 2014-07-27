<?php namespace Nestor\Repositories;

use Parameter;

class DbParameterRepository implements ParameterRepository {

	public function all()
	{
		return Parameter::all();
	}

	public function find($id)
	{
		return Parameter::findOrFail($id);
	}

	public function create($parameter_type_id, $report_id, $name, $description)
	{
		return Parameter::create(compact('parameter_type_id', 'report_id', 'name', 'description'));
	}

	public function update($id, $parameter_type_id, $report_id, $name, $description)
	{
		$entity = $this->find($id);
		$entity->fill(compact('parameter_type_id', 'report_id', 'name', 'description'))->save();
		return $entity;
	}

	public function delete($id)
	{
		return Parameter::where('id', $id)->delete();
	}

}

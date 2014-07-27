<?php namespace Nestor\Repositories;

use ParameterType;

class DbParameterTypeRepository implements ParameterTypeRepository {

	public function all()
	{
		return ParameterType::all();
	}

	public function find($id)
	{
		return ParameterType::findOrFail($id);
	}

	public function create($id, $name)
	{
		return ParameterType::create(compact('id', 'name'));
	}

	public function update($id, $name)
	{
		$entity = $this->find($id);
		$entity->fill(compact('name'))->save();
		return $entity;
	}

	public function delete($id)
	{
		return ParameterType::where('id', $id)->delete();
	}

}

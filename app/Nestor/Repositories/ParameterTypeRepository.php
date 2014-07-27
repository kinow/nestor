<?php namespace Nestor\Repositories;

interface ParameterTypeRepository {

	public function all();

	public function find($id);

	public function create($id, $name);

	public function update($id, $name);

	public function delete($id);

}
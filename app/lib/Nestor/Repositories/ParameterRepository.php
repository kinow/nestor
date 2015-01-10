<?php namespace Nestor\Repositories;

interface ParameterRepository {

	public function all();

	public function find($id);

	public function create($parameter_type_id, $report_id, $name, $description);

	public function update($id, $parameter_type_id, $report_id, $name, $description);

	public function delete($id);

}
<?php namespace Nestor\Repositories;

interface ReportTypeRepository {

	public function all();

	public function find($id);

	public function create($id, $name, $description);

	public function update($id, $name, $description);

	public function delete($id);

}
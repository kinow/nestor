<?php namespace Nestor\Repositories;

interface ReportRepository {

	public function all();

	public function find($id);

	public function create($report_type_id, $name, $description);

	public function update($id, $report_type_id, $name, $description);

	public function delete($id);

}
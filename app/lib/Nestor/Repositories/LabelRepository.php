<?php namespace Nestor\Repositories;

interface LabelRepository {

	public function all($projectId);

	public function find($id);

	public function create($projectId, $name, $color);

	public function update($id, $name, $color);

	public function delete($id);

}
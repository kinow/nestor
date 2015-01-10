<?php namespace Nestor\Repositories;

interface Repository {

	public function all();

	public function allWith(array $with);

	public function create($input);

	public function update($id, $input);

	public function find($id);

	public function findWith($id, array $with);

	public function paginate($perPage);

	public function paginateWith($perPage = 10, array $with);

	public function delete($id);
}
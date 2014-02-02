<?php namespace Nestor\Repositories;

use Auth, Hash, Validator;
use \ExecutionType;

class DbExecutionTypeRepository implements ExecutionTypeRepository {

	/**
	 * Get all of the project statuses.
	 *
	 * @return array
	 */
	public function all()
	{
		return ExecutionType::all();
	}

	/**
	 * Get a ExecutionType by their primary key.
	 *
	 * @param  int   $id
	 * @return ExecutionType
	 */
	public function find($id)
	{
		return ExecutionType::findOrFail($id);
	}

	/**
	 * Create a execution type
	 *
	 * @param  string  $name
	 * @param  string  $description
	 * @return ExecutionType
	 */
	public function create($name, $description)
	{
		return ExecutionType::create(compact('name', 'description'));
	}

	/**
	 * Update a execution type
	 *
	 * @param  int  $id
	 * @param  string  $name
	 * @param  string  $description
	 * @return ExecutionType
	 */
	public function update($id, $name, $description)
	{
		$execution_type = $this->find($id);

		$execution_type->fill(compact('name', 'description'))->save();

		return $execution_type;
	}

	/**
	 * Delete a execution type
	 * @param int $id
	 */
	public function delete($id)
	{
		return ExecutionType::where('id', $id)->delete();
	}

}

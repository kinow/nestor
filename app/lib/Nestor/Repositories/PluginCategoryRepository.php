<?php namespace Nestor\Repositories;

interface PluginCategoryRepository {

	/**
	 * Get all plugin categories
	 *
	 * @return PluginCategory
	 */
	public function all();

	/**
	 * Get a PluginCategory by their primary key.
	 *
	 * @param  int   $id
	 * @return PluginCategory
	 */
	public function find($id);

	/**
	 * Create a plugin category
	 *
	 * @param  string  $name
	 * @param  string  $description
	 * @return PluginCategory
	 */
	public function create($name, $description);

	/**
	 * Update a plugin category
	 *
	 * @param  int  $id
	 * @param  string  $name
	 * @param  string  $description
	 * @return PluginCategory
	 */
	public function update($id, $name, $description);

	/**
	 * Delete a plugin category
	 *
	 * @param int $id
	 */
	public function delete($id);

}
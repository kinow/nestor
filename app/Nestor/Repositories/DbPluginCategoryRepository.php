<?php namespace Nestor\Repositories;

use Auth, Hash, Validator;
use \PluginCategory;

class DbPluginCategoryRepository implements PluginCategoryRepository {

	/**
	 * Get all of the plugin categories.
	 *
	 * @return array
	 */
	public function all()
	{
		return PluginCategory::all();
	}

	/**
	 * Get a PluginCategory by their primary key.
	 *
	 * @param  int   $id
	 * @return PluginCategory
	 */
	public function find($id)
	{
		return PluginCategory::findOrFail($id);
	}

	/**
	 * Create a plugin category
	 *
	 * @param  string  $name
	 * @param  string  $description
	 * @return PluginCategory
	 */
	public function create($name, $description)
	{
		return PluginCategory::create(compact('name', 'description'));
	}

	/**
	 * Update a plugin category
	 *
	 * @param  int  $id
	 * @param  string  $name
	 * @param  string  $description
	 * @return PluginCategory
	 */
	public function update($id, $name, $description)
	{
		$pluginCategory = $this->find($id);

		$pluginCategory->fill(compact('name', 'description'))->save();

		return $pluginCategory;
	}

	/**
	 * Delete a plugin category
	 * @param int $id
	 */
	public function delete($id)
	{
		return PluginCategory::where('id', $id)->delete();
	}

}

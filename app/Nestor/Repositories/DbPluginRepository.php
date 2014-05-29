<?php namespace Nestor\Repositories;

use \Plugin;

class DbPluginRepository implements PluginRepository {

	/**
	 * Get all plugins
	 *
	 * @return Plugin
	 */
	public function all()
	{
		return Plugin::all();
	}

	/**
	 * Get a Plugin by their primary key.
	 *
	 * @param  int   $id
	 * @return Plugin
	 */
	public function find($id)
	{
		return Plugin::findOrFail($id);
	}

	public function findByName($name)
	{
		return Plugin::where('name', '=', $name)
			->first();
	}

	/**
	 * Create a plugin
	 *
	 * @param  string  $name
	 * @param  string  $slug
	 * @param  string  $description
	 * @param  string  $version
	 * @param  string  $author
	 * @param  string  $url
	 * @param  string  $status
	 * @param  date    $released_at
	 * @param  integer $plugin_category_id
	 * @return Plugin
	 */
	public function create($name, $slug, $description, $version, $author, $url, $status, $released_at, $plugin_category_id)
	{
		return Plugin::create(compact('name', 'slug', 'description', 'version', 'author', 'url', 'status', 'released_at', 'plugin_category_id'));
	}

	/**
	 * Update a plugin
	 *
	 * @param  int  $id
	 * @param  string  $name
	 * @param  string  $slug
	 * @param  string  $description
	 * @param  string  $version
	 * @param  string  $author
	 * @param  string  $url
	 * @param  string  $status
	 * @param  date    $released_at
	 * @param  integer $plugin_category_id
	 * @return Plugin
	 */
	public function update($id, $name, $slug, $description, $version, $author, $url, $status, $released_at, $plugin_category_id)
	{
		$plugin = $this->find($id);

		$plugin->fill(compact('name', 'slug', 'description', 'version', 'author', 'url', 'status', 'released_at', 'plugin_category_id'))->save();

		return $plugin;
	}

	/**
	 * Delete a plugin
	 *
	 * @param int $id
	 */
	public function delete($id)
	{
		return Plugin::where('id', $id)->delete();
	}

}
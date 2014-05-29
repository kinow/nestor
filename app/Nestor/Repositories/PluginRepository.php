<?php namespace Nestor\Repositories;

interface PluginRepository {

	/**
	 * Get all plugins
	 *
	 * @return Plugin
	 */
	public function all();

	/**
	 * Get a Plugin by their primary key.
	 *
	 * @param  int   $id
	 * @return Plugin
	 */
	public function find($id);

	public function findByName($name);

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
	public function create($name, $slug, $description, $version, $author, $url, $status, $released_at, $plugin_category_id);

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
	public function update($id, $name, $slug, $description, $version, $author, $url, $status, $released_at, $plugin_category_id);

	/**
	 * Delete a plugin
	 *
	 * @param int $id
	 */
	public function delete($id);

}
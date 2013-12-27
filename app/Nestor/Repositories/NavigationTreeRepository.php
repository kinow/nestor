<?php namespace Nestor\Repositories;

interface NavigationTreeRepository {

	/**
	 * Get all navigation tree nodes
	 *
	 * @return NavigationTreeNode
	 */
	public function all();

	/**
	 * Get a NavigationTreeNode by their primary key.
	 *
	 * @param  int   $id
	 * @return NavigationTreeNode
	 */
	public function find($id);

	/**
	 * Create a navigation tree node
	 *
	 * @param  int     $node_id
	 * @param  int     $node_type_id
	 * @param  int     $parent_id
	 * @param  string  $display_name
	 * @return NavigationTreeNode
	 */
	public function create($node_id, $node_type_id, $parent_id, $display_name);

	/**
	 * Update a navigation tree node
	 *
	 * @param  int     $id
	 * @param  int     $node_id
	 * @param  int     $node_type_id
	 * @param  int     $parent_id
	 * @param  string  $display_name
	 * @return NavigationTreeNode
	 */
	public function update($id, $node_id, $node_type_id, $parent_id, $display_name);

	/**
	 * Delete a navigation tree node
	 *
	 * @param int $id
	 */
	public function delete($id);

}
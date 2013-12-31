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
	 * @param  int   $ancestorId
	 * @param  int   $descendantId
	 * @return NavigationTreeNode
	 */
	public function find($nodeId, $nodeTypeId);

	/**
	 * Create a navigation tree node
	 *
	 * @param  string  $ancestor
	 * @param  string  $descendant
	 * @param  int     $node_id
	 * @param  int     $node_type_id
	 * @param  string  $display_name
	 * @return NavigationTreeNode
	 */
	public function create($ancestor, $descendant, $node_id, $node_type_id, $display_name);

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
	 * @param int $ancestor
	 * @param int $descendant
	 */
	public function delete($ancestor, $descendant);

	/**
	 * Delete a navigation tree node with all its children nodes
	 *
	 * @param int $ancestor
	 */
	public function deleteWithAllChildren($ancestor);

}
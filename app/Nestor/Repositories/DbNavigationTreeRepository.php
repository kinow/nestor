<?php namespace Nestor\Repositories;

use Auth, Hash, Validator;
use \NavigationTreeNode;

class DbNavigationTreeRepository implements NavigationTreeRepository {

	/**
	 * Get all navigation tree nodes
	 *
	 * @return NavigationTreeNode
	 */
	public function all()
	{
		return NavigationTreeNode::all();
	}

	/**
	 * Get a NavigationTreeNode by their primary key.
	 *
	 * @param  int   $id
	 * @return NavigationTreeNode
	*/
	public function find($id)
	{
		return NavigationTreeNode::findOrFail($id);
	}

	/**
	 * Create a navigation tree node
	 *
	 * @param  int     $node_id
	 * @param  int     $node_type_id
	 * @param  int     $parent_id
	 * @param  string  $display_name
	 * @return NavigationTreeNode
	*/
	public function create($node_id, $node_type_id, $parent_id, $display_name)
	{
		return NavigationTreeNode::create(compact('node_id', 'node_type_id', 'parent_id', 'display_name'));
	}

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
	public function update($id, $node_id, $node_type_id, $parent_id, $display_name)
	{
		$navigation_tree_node = $this->find($id);

		$navigation_tree_node->fill(compact('node_id', 'node_type_id', 'parent_id', 'display_name'))->save();

		return $navigation_tree_node;
	}

	/**
	 * Delete a navigation tree node
	 *
	 * @param int $id
	*/
	public function delete($id)
	{
		return NavigationTreeNode::where('id', $id)->delete();
	}

}

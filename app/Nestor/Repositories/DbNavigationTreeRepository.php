<?php namespace Nestor\Repositories;

use Auth, Hash, Validator, DB, Log;
use \NavigationTreeNode;

//http://www.mysqlperformanceblog.com/2011/02/14/moving-subtrees-in-closure-table/
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
	 * Get a NavigationTreeNode by its ancestor and length, thus returning all
	 * its children, itself included.
	 */
	public function children($ancestor, $length)
	{
		Log::info(sprintf('Retriving children for %s, length %d', $ancestor, $length));
		return NavigationTreeNode::
				where('ancestor', $ancestor)->
				where('length', '<=', $length)->
				get();
	}

	/**
	 * Get a NavigationTreeNode by their primary key.
	 *
	 * @param  int   $ancestorId
	 * @param  int   $descendantId
	 * @return NavigationTreeNode
	 */
	public function find($ancestorId, $descendantId)
	{
		return NavigationTreeNode::
				where('ancestor', '=', $ancestorId)->
				where('descendant', '=', $descendantId)->
				firstOrFail();
	}

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
	public function create($ancestor, $descendant, $node_id, $node_type_id, $display_name)
	{
		$created_at = new \DateTime();
		$created_at = $created_at->format('Y-m-d H:m:s');
		$updated_at = $created_at;
		//return NavigationTreeNode::create(compact('ancestor', 'descendant', 'length', 'node_id', 'node_type_id', 'parent_id', 'display_name'));
		return DB::insert(sprintf(
				"INSERT INTO %s(" .
				"ancestor, descendant, length, node_id, node_type_id, display_name, created_at, updated_at) " .
				"SELECT t.ancestor, '%s', t.length+1, %d, %d, '%s', '%s', '%s' " .
				"FROM %s AS t " .
				"WHERE t.descendant = '%s' " .
				"UNION ALL " .
				"SELECT '%s', '%s', 0, %d, %d, '%s', '%s', '%s'",

				'navigation_tree',
				$descendant,
				$node_id,
				$node_type_id,
				$display_name,
				$created_at,
				$updated_at,
				'navigation_tree',
				$ancestor,
				$descendant,
				$descendant,
				$node_id,
				$node_type_id,
				$display_name,
				$created_at,
				$updated_at
			));
	}

	/**
	 * Update a navigation tree node
	 *
	 * @param  string  $ancestor
	 * @param  string  $descendant
	 * @param  int     $node_id
	 * @param  int     $node_type_id
	 * @param  string  $display_name
	 * @return NavigationTreeNode
	*/
	public function update($ancestor, $descendant, $node_id, $node_type_id, $display_name)
	{
		$navigation_tree_node = $this->find($ancestor, $descendant);

		$navigation_tree_node->fill(compact('ancestor', 'descendant', 'node_id', 'node_type_id', 'display_name'))->save();

		return $navigation_tree_node;
	}

	/**
	 * Delete a navigation tree node
	 *
	 * @param int $ancestor
	 * @param int $descendant
	 */
	public function delete($ancestor, $descendant)
	{
		return NavigationTreeNode::where('ancestor', $ancestor)->where('descendant', $descendant)->delete();
	}

	/**
	 * Delete a navigation tree node with all its children nodes
	 *
	 * @param int $ancestor
	 */
	public function deleteWithAllChildren($ancestor)
	{
		return NavigationTreeNode::where('ancestor', $ancestor)->delete();
	}

}

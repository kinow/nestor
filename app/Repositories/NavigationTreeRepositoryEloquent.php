<?php
/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 Bruno P. Kinoshita, Peter Florijn
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Nestor\Repositories;

use DateTime;
use DB;
use Eloquent;
use Log;
use Nestor\Entities\NavigationTree;

/**
 * Class NavigationTreeRepositoryEloquent
 *
 * @package namespace Nestor\Repositories;
 */
class NavigationTreeRepositoryEloquent implements NavigationTreeRepository
{
    /**
     * 
     * {@inheritDoc}
     * @see \Nestor\Repositories\NavigationTreeRepository::children()
     */
	public function children($ancestor, $length = 1)
	{
		Log::info(sprintf('Retrieving children for %s, length %d', $ancestor, $length));
		$children = DB::table('navigation_tree AS a')
			->select(DB::raw("a.*"))
			->leftJoin('navigation_tree AS b', 'a.ancestor', '=', 'b.descendant')
			->where('b.ancestor', '=', "$ancestor")
			->where('a.length', '<=', $length)
			->groupBy('a.ancestor')->groupBy('a.descendant')->groupBy('a.length')
			->orderBy('a.ancestor')
			->get();
		$navigationTreeNodes = array();
		Eloquent::unguard();
// 		foreach ($children as $child) {
// 			$navigationTreeNodes[] = new Node(get_object_vars($child));
// 		}
		//return new Nodes($navigationTreeNodes);
		return $children;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Nestor\Repositories\NavigationTreeRepository::parents()
	 */
	public function parents($descendant)
	{
		Log::info(sprintf('Retrieving parents for %s', $descendant));
		return DB::table('navigation_tree AS a')
			->select(DB::raw("a.*"))
			->leftJoin('navigation_tree AS b', 'b.descendant', '=', 'a.ancestor')
			->where('a.ancestor', '=', "$descendant")
			->groupBy('ancestor')->groupBy('descendant')->groupBy('length')
			->get();
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Nestor\Repositories\NavigationTreeRepository::parent_()
	 */
	public function parent_($descendant)
	{
		return DB::table('navigation_tree AS a')
			->select(DB::raw("a.*"))
			->where('descendant', '=', $descendant)
			->where('ancestor', '<>', $descendant)
			->where('length', '=', 1)
			->first();
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Nestor\Repositories\NavigationTreeRepository::find()
	 */
	public function find($ancestorId, $descendantId)
	{
		return NavigationTree::where('ancestor', '=', $ancestorId)
			->where('descendant', '=', $descendantId)
			->firstOrFail();
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Nestor\Repositories\NavigationTreeRepository::create()
	 */
	public function create($ancestor, $descendant, $node_id, $node_type_id, $display_name)
	{
		$created_at = new DateTime();
		$created_at = $created_at->format('Y-m-d H:m:s');
		$updated_at = $created_at;
		$created =  DB::insert(
			'INSERT INTO navigation_tree(' .
			'ancestor, descendant, length, node_id, node_type_id, display_name, created_at, updated_at) ' .
			'SELECT t.ancestor, ?, t.length+1, ?, ?, ?, ?, ? ' .
			'FROM navigation_tree AS t ' .
			'WHERE t.descendant = ? ' .
			'UNION ALL ' .
			'SELECT ?, ?, 0, ?, ?, ?, ?, ? ',
			[
				$descendant,
				$node_id,
				$node_type_id,
				$display_name,
				$created_at,
				$updated_at,
				$ancestor,
				$descendant,
				$descendant,
				$node_id,
				$node_type_id,
				$display_name,
				$created_at,
				$updated_at
			]
		);
		return $this->find($ancestor, $descendant);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Nestor\Repositories\NavigationTreeRepository::update()
	 */
	public function update($ancestor, $descendant, $node_id, $node_type_id, $display_name)
	{
		Log::debug(sprintf('Updating node ancestor %s descendant %s', $ancestor, $descendant));
		$node = NavigationTree::where('ancestor', '=', $ancestor)
			->where('descendant', '=', $descendant)
			->firstOrFail();
		$node->fill(compact('ancestor', 'descendant', 'node_id', 'node_type_id', 'display_name'))->save();
		return $node;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Nestor\Repositories\NavigationTreeRepository::updateDisplayNameByDescendant()
	 */
	public function updateDisplayNameByDescendant($descendantId, $display_name)
	{
		$affectedRows = NavigationTree::where('descendant', '=', $descendantId)
			->update(array('display_name' => $display_name));
		return $affectedRows;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Nestor\Repositories\NavigationTreeRepository::delete()
	 */
	public function delete($descendant)
	{
		return NavigationTree::where('descendant', $descendant)->delete();
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Nestor\Repositories\NavigationTreeRepository::deleteWithAllChildren()
	 */
	public function deleteWithAllChildren($ancestor, $descendant)
	{
		return NavigationTree::where('ancestor', $ancestor)
			->orWhere('descendant', $descendant)
			->delete();
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Nestor\Repositories\NavigationTreeRepository::containsChildrenWithName()
	 */
	public function containsChildrenWithName($ancestor, $name)
	{
		Log::info(sprintf('Retrieving children for %s, length %d', $ancestor, 1));
		$children = DB::table('navigation_tree AS a')
			->select(DB::raw("b.*"))
			->leftJoin('navigation_tree AS b', 'a.ancestor', '=', 'b.descendant')
			->where('b.ancestor', '=', $ancestor)
			->where('b.length', '<=', 1)
			->where('b.display_name', $name)
			->groupBy('a.ancestor')->groupBy('a.descendant')->groupBy('a.length')
			->orderBy('a.ancestor')
			->get();
		return ($children && !empty($children));
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Nestor\Repositories\NavigationTreeRepository::move()
	 */
	public function move($descendant, $ancestor)
	{
		$node = $this->find($descendant, $descendant);
		if ($this->containsChildrenWithName($ancestor, $node['display_name'])) {
			throw new Exception(sprintf('Duplicate node name %s', $node['display_name']));
		}
		DB::beginTransaction();
		try {
			// Log::debug($node);
			$this->delete($descendant);
			// $ancestor, $descendant, $node_id, $node_type_id, $display_name
			$this->create(
				$ancestor, 
				$descendant, 
				$node['node_id'], 
				$node['node_type_id'], 
				$node['display_name']
			);
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			Log::error($e);
			throw $e;
		}
	}
	
}
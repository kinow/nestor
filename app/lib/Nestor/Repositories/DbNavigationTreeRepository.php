<?php
namespace Nestor\Repositories;

use DateTime;
use Exception;

use Auth;
use Hash;
use Validator;
use DB;
use Log;
use Eloquent;

use Magniloquent\Magniloquent\Magniloquent;
use Nestor\Model\Node;
use Nestor\Model\Nodes;

//http://www.mysqlperformanceblog.com/2011/02/14/moving-subtrees-in-closure-table/
class DbNavigationTreeRepository implements NavigationTreeRepository {

	public function all()
	{
		return Node::all();
	}

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
		foreach ($children as $child) {
			$navigationTreeNodes[] = new Node(get_object_vars($child));
		}

		return new Nodes($navigationTreeNodes);
	}

	public function parents($descendant)
	{
		Log::info(sprintf('Retrieving parents for %s', $descendant));
// 		return Node::
// 			where('descendant', $descendant)->
// 			get();
/*
select a.* from navigation_tree a
left join navigation_tree b on b.descendant = a.ancestor
where a.descendant = '2-8'
group by a.ancestor, a.descendant, a.length
order by a.length
 */
		return DB::table('navigation_tree AS a')
			->select(DB::raw("a.*"))
			->leftJoin('navigation_tree AS b', 'b.descendant', '=', 'a.ancestor')
			->where('a.ancestor', '=', "$descendant")
			->groupBy('ancestor')->groupBy('descendant')->groupBy('length')
			->get();
	}

	public function parent($descendant)
	{
		return DB::table('navigation_tree AS a')
			->select(DB::raw("a.*"))
			->where('descendant', '=', $descendant)
			->where('ancestor', '<>', $descendant)
			->where('length', '=', 1)
			->first();
	}

	public function find($ancestorId, $descendantId)
	{
		return Node::where('ancestor', '=', $ancestorId)
			->where('descendant', '=', $descendantId)
			->firstOrFail()
			->toArray();
	}

	public function create($ancestor, $descendant, $node_id, $node_type_id, $display_name)
	{
		$created_at = new DateTime();
		$created_at = $created_at->format('Y-m-d H:m:s');
		$updated_at = $created_at;
		//return Node::create(compact('ancestor', 'descendant', 'length', 'node_id', 'node_type_id', 'parent_id', 'display_name'));
		$created =  DB::insert(sprintf(
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
		return $this->find($ancestor, $descendant);
	}

	public function update($ancestor, $descendant, $node_id, $node_type_id, $display_name)
	{
		Log::debug(sprintf('Updating node ancestor %s descendant %s', $ancestor, $descendant));
		$node = Node::where('ancestor', '=', $ancestor)
			->where('descendant', '=', $descendant)
			->firstOrFail();
		Log::debug(var_export($node));
		$node->fill(compact('ancestor', 'descendant', 'node_id', 'node_type_id', 'display_name'))->save();
		return $node;
	}

	public function updateDisplayNameByDescendant($descendantId, $display_name)
	{
		$affectedRows = Node::where('descendant', '=', $descendantId)
			->update(array('display_name' => $display_name));
		return $affectedRows;
	}

	public function delete($descendant)
	{
		return Node::where('descendant', $descendant)->delete();
	}

	public function deleteWithAllChildren($ancestor, $descendant)
	{
		return Node::where('ancestor', $ancestor)
			->orWhere('descendant', $descendant)
			->delete();
	}

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
		Log::debug(var_export($children, true));
		return ($children && !empty($children));
	}

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

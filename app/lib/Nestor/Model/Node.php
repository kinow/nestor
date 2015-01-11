<?php
namespace Nestor\Model;

class Node extends BaseModel
{

	protected $table = 'navigation_tree';

	protected $primaryKey = 'ancestor';

	public $incrementing = FALSE;

	public $children = array();

	protected $fillable = array('node_id', 'node_type_id', 'display_name');

	protected $hidden = array('children');

	protected static $_rules = array(
		"create" => array(
				'ancestor' => 'required',
				'descendant' => 'required',
				'length' => '',
				'node_id' => 'required|numeric',
				'node_type_id' => 'required|numeric',
				'display_name' => 'required|min:2'
		),
		"update" => array(
				'ancestor' => 'required',
				'descendant' => 'required',
				'length' => '',
				'node_id' => 'required|numeric',
				'node_type_id' => 'required|numeric',
				'display_name' => 'required|min:2'
		)
	);

	protected static $purgeable = [''];

	public function getAncestorExecutionType()
	{
		$ancestor = $this->ancestor;
		if (!$ancestor)
			throw new \Exception("Invalid ancestor");
		
		list($executionType, $nodeId) = explode("-", $ancestor);
		return $executionType;
	}
	
	public function getAncestorNodeId()
	{
		$ancestor = $this->ancestor;
		if (!$ancestor)
			throw new \Exception("Invalid ancestor");
	
		list($executionType, $nodeId) = explode("-", $ancestor);
		return $nodeId;
	}
	
	public function getDescendantExecutionType()
	{
		$descendant = $this->descendant;
		if (!$descendant)
			throw new \Exception("Invalid descendant");
	
		list($executionType, $nodeId) = explode("-", $descendant);
		return (int) $executionType;
	}
	
	public function getDescendantNodeId()
	{
		$descendant = $this->descendant;
		if (!$descendant)
			throw new \Exception("Invalid descendant");
	
		list($executionType, $nodeId) = explode("-", $descendant);
		return $nodeId;
	}

}
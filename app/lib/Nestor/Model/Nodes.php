<?php
namespace Nestor\Model;

use HTML;
use Fhaculty\Graph\Graph as Graph;
use Fhaculty\Graph\Algorithm\Search\BreadthFirst;
//use Fhaculty\Graph\GraphViz;
use Fhaculty\Graph\Walk;

class Nodes 
{

	const PROJECT_TYPE = 1;
	const TEST_SUITE_TYPE = 2;
	const TEST_CASE_TYPE = 3;

	protected $nodes = array();

	public function __construct(Array $nodes)
	{
		if ($nodes && is_array($nodes)) {
			$this->nodes = $nodes;
		}
	}

	public function toArray()
	{
		$nodes = array();
		foreach ($this->nodes as $node) {
			$nodes[] = $node->toArray();
		}
		return $nodes;
	}

	// --- Utility methods

	public static function id($nodeType, $nodeId) 
	{
		return sprintf("%s-%s", $nodeType, $nodeId);
	}

}
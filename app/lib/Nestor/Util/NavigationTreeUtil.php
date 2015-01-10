<?php namespace Nestor\Util;

use Fhaculty\Graph\Graph as Graph;
use Fhaculty\Graph\Algorithm\Search\BreadthFirst;
//use Fhaculty\Graph\GraphViz;
use Fhaculty\Graph\Walk;

class NavigationTreeUtil {

	private function __construct() {}

	public static function createGraph($nodes) 
	{
		$graph = new Graph();
		$vertices = array();
		// first add all the nodes of the graph/tree
		foreach ($nodes as $node)
		{
			$node = (object) $node;
			if ($node->length == 0)
			{
				$vertex = $graph->createVertex($node->descendant, /* returnDuplicate */ TRUE);
				$vertex->data = $node;
				$vertices[$node->descendant] = $vertex;
			}
		}

		// now create the edges
		foreach ($nodes as $node)
		{
			$node = (object) $node;
			if ($node->length != 0)
			{
				$from = $vertices[$node->ancestor]; // get the parent node
				$to = $vertices[$node->descendant]; // the destination node
				
				$from->createEdgeTo($to);
			}
		}

		return $graph;
	}

	public static function createNavigationTree($nodes, $root)
	{
		$graph = static::createGraph($nodes);

		$rootVertex = new BreadthFirst($vertices[$root]);
		$bfsVertices = $rootVertex->getVertices();
		$tree = array();
		$node = $vertices[$root]->data;
		$tree[$node->descendant] = $node;
		static::createTreeFromVertex($vertices[$root]);

		return $tree;
	}

	public static function createTreeFromVertex($vertex) 
	{
		$node = $vertex->data;
		$node->children = array();
		foreach ($vertex->getEdgesOut() as $edge) {
			$childVertex = $edge->getVertexEnd();
			$node->children[] = $childVertex->data;
			static::createTreeFromVertex($childVertex);
		}
	}

}

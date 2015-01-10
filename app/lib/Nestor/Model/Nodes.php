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

	public function asNavigationTree() 
	{
		$graph = new Graph();
		$vertices = array();
		// first add all the nodes of the graph/tree
		foreach ($this->nodes as $node)
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
		foreach ($this->nodes as $node)
		{
			$node = (object) $node;
			if ($node->length != 0)
			{
				$from = $vertices[$node->ancestor]; // get the parent node
				$to = $vertices[$node->descendant]; // the destination node
				
				$from->createEdgeTo($to);
			}
		}

		$rootVertex = new BreadthFirst($vertices[$root]);
		$bfsVertices = $rootVertex->getVertices();
		$tree = array();
		$node = $vertices[$root]->data;
		$tree[$node->descendant] = $node;
		$this->createTreeFromVertex($vertices[$root]);

		return $tree;
	}

	public function asHtml($navigationTree = array(), $nodeId, $themeName = '') 
	{
		$buffer = '';
		if (is_null ( $navigationTree ) || empty ( $navigationTree ))
			return $buffer;

		foreach ($navigation_tree as $node) {
			$extra_classes = "";
			if ($node->descendant == $nodeId && $node->ancestor == $nodeId) {
				$extra_classes = " active";
			}
			if ($node->node_type_id == 1) { // project
				$buffer .= "<ul id='treeData' style='display: none;'>";
				$buffer .= sprintf ("<li data-icon='places/folder.png' data-node-type='%s' data-node-id='%s' class='expanded%s'>%s", 
					$node->node_type_id, 
					$node->node_id,
					$extra_classes, 
					HTML::link('/specification/nodes/' . $node->descendant, $node->display_name, array('target' => '_self')
				));
				if (! empty ( $node->children )) {
					$buffer .= "<ul>";
					$buffer .= $this->createTreeHTML($node->children, $nodeId, $themeName);
					$buffer .= "</ul>";
				}
				$buffer .= "</li></ul>";
			} else if ($node->node_type_id == 2) { // test suite
				$buffer .= sprintf("<li data-icon='actions/document-open.png' data-node-type='%s' data-node-id='%s' class='expanded%s'>%s", 
					$node->node_type_id, 
					$node->node_id,
					$extra_classes, 
					HTML::link('/specification/nodes/' . $node->descendant, $node->display_name, array('target' => '_self')
				));
				if (! empty ( $node->children )) {
					$buffer .= "<ul>";
					$buffer .= $this->createTreeHTML($node->children, $nodeId, $themeName);
					$buffer .= "</ul>";
				}
				$buffer .= "</li>";
			} else {
				$buffer .= sprintf("<li data-icon='mimetypes/text-x-generic.png' data-node-type='%s' data-node-id='%s' class='%s'>%s</li>", 
					$node->node_type_id, 
					$node->node_id,
					$extra_classes, 
					HTML::link ('/specification/nodes/' . $node->descendant, $node->display_name, array('target' => '_self')
				));
			}
		}

		return $buffer;
	}

	public function containsNode($node) 
	{
		if ($node == NULL || !isset($node))
			return FALSE;

		foreach ($tree as $entry) {
			if ($entry->ancestor === $node->ancestor && $entry->descendant === $node->descendant) {
				return TRUE;
			}
			if (isset($entry->children) && !empty($entry->children)) {
				if ($this->containsNode($entry->children, $node)) {
					return TRUE;
				}
			}
		}
		return FALSE;
	}

	// --- Utility methods

	protected function createTreeFromVertex($vertex) 
	{
		$node = $vertex->data;
		$node->children = array();
		foreach ($vertex->getEdgesOut() as $edge) {
			$childVertex = $edge->getVertexEnd();
			$node->children[] = $childVertex->data;
			$this->createTreeFromVertex($childVertex);
		}
	}

	public static function id($nodeType, $nodeId) 
	{
		return sprintf("%s-%s", $nodeType, $nodeId);
	}

}
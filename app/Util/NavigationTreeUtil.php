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

namespace Nestor\Util;

use Fhaculty\Graph\Graph as Graph;
use Fhaculty\Graph\Vertex;
use Graphp\Algorithms\Search\BreadthFirst;
use HTML;
use Nestor\Model\Nodes;

/**
 * Utility methods for the NavigationTree.
 *
 * @author Bruno P. Kinoshita
 * @since 0.12
 */
class NavigationTreeUtil
{
    /**
     * Hidden constructor.
     */
    private function __construct()
    {
    }
    
    /**
     * Create a graph given an array of nodes, and a root node.
     *
     * @param Array $nodes
     * @param string $root
     * @return Array
     */
    public static function createNavigationTree($nodes, $root)
    {
        list($graph, $vertices) = static::createGraph($nodes);
        // Do a breadth first search to construct the desired set of vertices
        $rootVertex = new BreadthFirst($vertices[$root]);
        $bfsVertices = $rootVertex->getVertices();
        // $tree is the result object. Some of its elements are modified by other function, by reference.
        $tree = array();
        $node = $vertices[$root]->data;
        $tree[] = $node;
        // Here $vertices gets its data element modified. The data element will get a children object, with all the
        // children nodes of the root vertex.
        static::createTreeFromVertex($vertices[$root]);
        return $tree;
    }
    
    /**
     * Create a graph with the given nodes.
     *
     * @param Array $nodes
     */
    private static function createGraph($nodes)
    {
        $graph = new Graph();
        $vertices = array ();
        // first add all the nodes of the graph/tree
        foreach ($nodes as $node) {
            $node = (object) $node;
            if ($node->length ==0) {
                $vertex = $graph->createVertex($node->descendant, /* returnDuplicate */ true);
                $vertex->data = $node;
                $vertices [$node->descendant] = $vertex;
            }
        }
        // now create the edges
        foreach ($nodes as $node) {
            $node = (object) $node;
            if ($node->length !=0) {
                $from = $vertices [$node->ancestor]; // get the parent node
                $to = $vertices [$node->descendant]; // the destination node
                
                $from->createEdgeTo($to);
            }
        }
        return array (
            $graph,
            $vertices
        );
    }
    
    /**
     * Recursive function, that creates a tree from one single vertex/node.
     * It modifies the
     * given vertex, adding its edges as children in its data attribute.
     *
     * @param Vertex $vertex
     */
    private static function createTreeFromVertex($vertex)
    {
        $node = $vertex->data;
        $node->children = array();
        foreach ($vertex->getEdgesOut() as $edge) {
            $childVertex = $edge->getVertexEnd();
            $node->children[] = $childVertex->data;
            static::createTreeFromVertex($childVertex);
        }
    }

    public static function containsNode($tree, $node)
    {
        if ($node == null || !isset($node)) {
            return false;
        }
        foreach ($tree as $entry) {
            if ($entry->ancestor === $node['ancestor'] && $entry->descendant === $node['descendant']) {
                return true;
            }
            if (isset($entry->children) && !empty($entry->children)) {
                if (static::containsNode($entry->children, $node)) {
                    return true;
                }
            }
        }
        return false;
    }
    public static function getAncestorNodeType($ancestor)
    {
        list ( $executionType, $nodeId ) = explode("-", $ancestor);
        return intval($executionType);
    }
    public static function getAncestorNodeId($ancestor)
    {
        list ( $executionType, $nodeId ) = explode("-", $ancestor);
        return $nodeId;
    }
    public static function getAncestorExecutionType($ancestor)
    {
        list($executionType, $nodeId) = explode("-", $ancestor);
        return (int) $executionType;
    }
    public static function getDescendantNodeType($descendant)
    {
        list ( $executionType, $nodeId ) = explode("-", $descendant);
        return intval($executionType);
    }
    public static function getDescendantNodeId($descendant)
    {
        list ( $executionType, $nodeId ) = explode("-", $descendant);
        return $nodeId;
    }
    public static function getDescendantExecutionType($descendant)
    {
        list($executionType, $nodeId) = explode("-", $descendant);
        return (int) $executionType;
    }
}

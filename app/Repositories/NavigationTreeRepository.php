<?php

namespace Nestor\Repositories;

use Nestor\Entities\NavigationTree;
/**
 * Interface NavigationTreeRepository
 *
 * @package namespace Nestor\Repositories;
 */
interface NavigationTreeRepository
{
    // TBD: is it going to be used?
    function children($ancestor, $length);
    
    // TBD: is it going to be used?
    function parents($descendant);
    
    // TBD: is it going to be used?
    function parent_($descendant);
    
    /**
     * Find the node whose by a given ancestor and by a given descendant.
     *
     * @param string $ancestorId            
     * @param string $descendantId   
     * @return NavigationTree         
     */
    function find($ancestorId, $descendantId);
    
    /**
     * Create a new node in the navigation tree.
     *
     * @param string $ancestor            
     * @param string $descendant            
     * @param integer $node_id            
     * @param integer $node_type_id            
     * @param string $display_name
     * @return NavigationTree
     */
    function create($ancestor, $descendant, $node_id, $node_type_id, $display_name);
    
    /**
     * Update a node.
     * @param string $ancestor
     * @param string $descendant
     * @param integer $node_id
     * @param integer $node_type_id
     * @param string $display_name
     * @return NavigationTree
     */
    function update($ancestor, $descendant, $node_id, $node_type_id, $display_name);
    
    // TBD: is it going to be used?
    function updateDisplayNameByDescendant($descendantId, $display_name);
    
    // TBD: is it going to be used?
    function delete($descendant);
    
    /**
     * Delete all nodes in the tree, where the ancestor OR the descendant
     * match the given values.
     *
     * @param string $ancestor            
     * @param string $descendant
     * @return bool
     */
    function deleteWithAllChildren($ancestor, $descendant);
    
    // TBD: is it going to be used?
    function containsChildrenWithName($ancestor, $name);
    
    // TBD: is it going to be used?
    function move($descendant, $ancestor);
}

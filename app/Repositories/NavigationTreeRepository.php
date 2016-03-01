<?php

namespace Nestor\Repositories;

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
    
    // TBD: is it going to be used?
    function find($ancestorId, $descendantId);
    
    /**
     * Creates a new node in the navigation tree.
     * 
     * @param string $ancestor            
     * @param string $descendant            
     * @param integer $node_id            
     * @param integer $node_type_id            
     * @param string $display_name            
     */
    function create($ancestor, $descendant, $node_id, $node_type_id, $display_name);
    
    // TBD: is it going to be used?
    function update($ancestor, $descendant, $node_id, $node_type_id, $display_name);
    
    // TBD: is it going to be used?
    function updateDisplayNameByDescendant($descendantId, $display_name);
    
    // TBD: is it going to be used?
    function delete($descendant);
    
    // TBD: is it going to be used?
    function deleteWithAllChildren($ancestor, $descendant);
    
    // TBD: is it going to be used?
    function containsChildrenWithName($ancestor, $name);
    
    // TBD: is it going to be used?
    function move($descendant, $ancestor);
}

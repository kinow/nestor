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

use Nestor\Entities\NavigationTree;

/**
 * Interface NavigationTreeRepository
 *
 * @package namespace Nestor\Repositories;
 */
interface NavigationTreeRepository
{
    /**
     * Returns the node and its descendents within a given length. So in the following
     * example.
     *
     * - Parent
     * -- Child A
     * --- Grandchild A0
     * --- Grandchild A1
     * -- Child B
     * --- Grandchild B0
     * -- Child C
     *
     * Asking for children of "Child A" with length equals 1 returns "Grandchild A0"
     * and "Grandchild A1".
     *
     * Asking for children of "Parent" with length equals 1 returns "Child A", "Child B" and
     * "Child C".
     *
     * Asking for children of "Parent" with length equals 2 (or greater) returns "Child A",
     * "Grandchild A0", "Grandchild A1", "Child B", "Grandchild B0" and "Child C". In other
     * words, the whole tree.
     *
     * @param string $ancestor
     * @param string $length
     */
    public function children($ancestor, $length);
    
    // TBD: is it going to be used?
    public function parents($descendant);
    
    // TBD: is it going to be used?
    public function parent_($descendant);
    
    /**
     * Find the node whose by a given ancestor and by a given descendant.
     *
     * @param string $ancestorId
     * @param string $descendantId
     * @return NavigationTree
     */
    public function find($ancestorId, $descendantId);
    
    /**
     * Create a new node in the navigation tree.
     *
     * @param string $ancestor
     * @param string $descendant
     * @param integer $node_id
     * @param integer $node_type_id
     * @param string $display_name
     * @param string $attributes
     * @return NavigationTree
     */
    public function create($ancestor, $descendant, $node_id, $node_type_id, $display_name, $attributes);
    
    /**
     * Update a node.
     * @param string $ancestor
     * @param string $descendant
     * @param integer $node_id
     * @param integer $node_type_id
     * @param string $display_name
     * @return NavigationTree
     */
    public function update($ancestor, $descendant, $node_id, $node_type_id, $display_name);
    
    // TBD: is it going to be used?
    public function updateDisplayNameByDescendant($descendantId, $display_name);
    
    // TBD: is it going to be used?
    public function delete($descendant);
    
    /**
     * Delete all nodes in the tree, where the ancestor OR the descendant
     * match the given values.
     *
     * @param string $ancestor
     * @param string $descendant
     * @return bool
     */
    public function deleteWithAllChildren($ancestor, $descendant);
    
    // TBD: is it going to be used?
    public function containsChildrenWithName($ancestor, $name);
    
    // TBD: is it going to be used?
    public function move($descendant, $ancestor);
}

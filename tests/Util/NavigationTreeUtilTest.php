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

namespace Repositories;

use \TestCase;
use Nestor\Util\NavigationTreeUtil;

class NavigationTreeUtilTest extends TestCase
{

    public function testCreateNavigationTree()
    {
        $nodes = [];
        $root = '1-1';

        // vertices

        $nodeA = $this->createNode('1-1', '1-1', [], 0);
        $nodeAA = $this->createNode('2-1', '2-1', [], 0);
        $nodeAB = $this->createNode('2-2', '2-2', [], 0);
        $nodeABA = $this->createNode('3-1', '3-1', [], 0);

        // edges

        $nodeA2NodeAA = $this->createNode('1-1', '2-1', [], 1);
        $nodeA2NodeAB = $this->createNode('1-1', '2-2', [], 1);
        $nodeAB2NodeABA = $this->createNode('2-2', '3-1', [], 1);

        $nodes[] = $nodeA;
        $nodes[] = $nodeAA;
        $nodes[] = $nodeAB;
        $nodes[] = $nodeABA;
        $nodes[] = $nodeA2NodeAA;
        $nodes[] = $nodeA2NodeAB;
        $nodes[] = $nodeAB2NodeABA;

        $navigationTree = NavigationTreeUtil::createNavigationTree($nodes, $root);
        $this->assertEquals(2, count($navigationTree[0]->children));
    }

    public function testContainsNodeTreeNodeNotFound()
    {
        $temp = $this->createNode('1-1', '1-1', []);

        $children = [];
        $children[] = $temp;

        $temp = $this->createNode('0-0', '0-0', $children);
        
        $tree = [];
        $tree[] = $temp;

        $node = [];
        $node['ancestor'] = '1-1';
        $node['descendant'] = '1-2';

        $this->assertFalse(NavigationTreeUtil::containsNode($tree, $node));
    }

    public function testContainsNodeTreeNodeFound()
    {
        $temp = $this->createNode('1-1', '1-1', []);

        $children = [];
        $children[] = $temp;

        $temp = $this->createNode('0-0', '0-0', $children);
        
        $tree = [];
        $tree[] = $temp;

        $node = [];
        $node['ancestor'] = '1-1';
        $node['descendant'] = '1-1';

        $this->assertTrue(NavigationTreeUtil::containsNode($tree, $node));
    }

    public function testContainsNodeFlatTreeNodeNotFound()
    {
        $temp = $this->createNode('1-1', '1-1', []);
        
        $tree = [];
        $tree[] = $temp;

        $node = [];
        $node['ancestor'] = '1-1';
        $node['descendant'] = '1-2';

        $this->assertFalse(NavigationTreeUtil::containsNode($tree, $node));
    }

    public function testContainsNodeFlatTreeNodeFound()
    {
        $temp = $this->createNode('1-1', '1-1', []);
        
        $tree = [];
        $tree[] = $temp;

        $node = [];
        $node['ancestor'] = '1-1';
        $node['descendant'] = '1-1';

        $this->assertTrue(NavigationTreeUtil::containsNode($tree, $node));
    }

    private function createNode($ancestor, $descendant, $children, $length = 0)
    {
        $node = new \stdClass();
        $node->ancestor = $ancestor;
        $node->descendant = $descendant;
        $node->children = $children;
        $node->length = $length;
        return $node;
    }

    public function testContainsNodeWithNullNode()
    {
        $tree = [];
        $this->assertFalse(NavigationTreeUtil::containsNode($tree, null));
    }

    public function testContainsNodeWithEmptyTree()
    {
        $tree = [];
        $this->assertFalse(NavigationTreeUtil::containsNode($tree, []));
    }

    public function testGetAncestorNodeType()
    {
        $expected = [1, 2, 100, 0, 1000];
        $tests = ['1-1', '2-100', '100-15', '0-0', '1000-3'];

        foreach ($expected as $key => $value) {
            $this->assertEquals($value, NavigationTreeUtil::getAncestorNodeType($tests[$key]));
        }
    }

    public function testGetAncestorNodeId()
    {
        $expected = [1, 100, 15, 0, 3];
        $tests = ['1-1', '2-100', '100-15', '0-0', '1000-3'];

        foreach ($expected as $key => $value) {
            $this->assertEquals($value, NavigationTreeUtil::getAncestorNodeId($tests[$key]));
        }
    }

    public function testGetDescendantNodeType()
    {
        $expected = [1, 2, 100, 0, 1000];
        $tests = ['1-1', '2-100', '100-15', '0-0', '1000-3'];

        foreach ($expected as $key => $value) {
            $this->assertEquals($value, NavigationTreeUtil::getDescendantNodeType($tests[$key]));
        }
    }

    public function testGetDescendantNodeId()
    {
        $expected = [1, 100, 15, 0, 3];
        $tests = ['1-1', '2-100', '100-15', '0-0', '1000-3'];

        foreach ($expected as $key => $value) {
            $this->assertEquals($value, NavigationTreeUtil::getDescendantNodeId($tests[$key]));
        }
    }
}

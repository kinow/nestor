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
use Nestor\Entities\NavigationTree;
use Nestor\Repositories\NavigationTreeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NavigationTreeRepositoryTest extends TestCase
{

    use DatabaseTransactions;

    public function testNavigationTree()
    {
        $ancestor = '10-10';
        $descendant = '10-10';
        $display_name = $this->faker->word;
        $node_id = $this->faker->randomDigitNotNull;
        $node_type_id = $this->faker->randomDigitNotNull;

        $navigationTreeRepository = app()->make(\Nestor\Repositories\NavigationTreeRepository::class);
        $navigationTree = $navigationTreeRepository->create($ancestor, $descendant, $node_id, $node_type_id, $display_name);

        $this->assertNotNull($navigationTree['created_at']);
        $this->assertEquals($ancestor, $navigationTree['ancestor']);
        $this->assertEquals($descendant, $navigationTree['descendant']);
        $this->assertEquals(0, $navigationTree['length']);
        $this->assertEquals($display_name, $navigationTree['display_name']);
        $this->assertEquals($node_id, $navigationTree['node_id']);
        $this->assertEquals($node_type_id, $navigationTree['node_type_id']);
    }
}

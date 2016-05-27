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

namespace Nestor\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class NavigationTree extends Model implements Transformable
{
    use TransformableTrait;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'navigation_tree';
    
    protected $primaryKey = 'ancestor';
    
    public $incrementing = false;
    
    /**
     * Fillable properties.
     * @var array
     */
    protected $fillable = [
            'ancestor',
            'descendant',
            'length',
            'node_id',
            'node_type_id',
            'display_name'
    ];
    
    const PROJECT_TYPE = 1;
    const TEST_SUITE_TYPE = 2;
    const TEST_CASE_TYPE = 3;
    
    /**
     * Get a project node ID.
     *
     * @param string $nodeId
     */
    public static function projectId($nodeId)
    {
        return static::nodeId(static::PROJECT_TYPE, $nodeId);
    }
    
    /**
     * Get a test suite node ID.
     *
     * @param string $nodeId
     */
    public static function testSuiteId($nodeId)
    {
        return static::nodeId(static::TEST_SUITE_TYPE, $nodeId);
    }
    
    /**
     * Get a test case node ID.
     *
     * @param string $nodeId
     */
    public static function testCaseId($nodeId)
    {
        return static::nodeId(static::TEST_CASE_TYPE, $nodeId);
    }
    
    /**
     * Get node ID.
     *
     * @param string $nodeType
     * @param string $nodeId
     */
    public static function nodeId($nodeType, $nodeId)
    {
        return sprintf("%s-%s", $nodeType, $nodeId);
    }
}

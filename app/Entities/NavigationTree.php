<?php

namespace Nestor\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class NavigationTree extends Model implements Transformable
{
    use TransformableTrait;
    
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
        return sprintf("%s-%s", static::PROJECT_TYPE, $nodeId);
    }
    
    /**
     * Get a test suite node ID.
     *
     * @param string $nodeId
     */
    public static function testSuiteId($nodeId)
    {
        return sprintf("%s-%s", static::TEST_SUITE_TYPE, $nodeId);
    }
    
    /**
     * Get a test case node ID.
     *
     * @param string $nodeId
     */
    public static function testCaseId($nodeId)
    {
        return sprintf("%s-%s", static::TEST_CASE_TYPE, $nodeId);
    }
    
    /**
     * Get node ID.
     *
     * @param string $nodeType            
     * @param string $nodeId            
     */
    public static function id($nodeType, $nodeId)
    {
        return sprintf("%s-%s", $nodeType, $nodeId);
    }
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'navigation_tree';
}

<?php

namespace Nestor\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class NavigationTree extends Model implements Transformable
{
    use TransformableTrait;
    protected $fillable = [ 
            'ancestor',
            'descendant',
            'length',
            'node_id',
            'node_type_id',
            'display_name' 
    ];
    
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
}

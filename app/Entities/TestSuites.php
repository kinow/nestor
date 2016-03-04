<?php

namespace Nestor\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class TestSuites extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['name', 'description', 'project_id', 'created_by'];
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'test_suites';

}

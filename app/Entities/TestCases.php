<?php

namespace Nestor\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class TestCases extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['project_id', 'test_suite_id'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'test_cases';

    public $version = null;
}

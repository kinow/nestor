<?php

namespace Nestor\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class ExecutionStatuses extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [];

}
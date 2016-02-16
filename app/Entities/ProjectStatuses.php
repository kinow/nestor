<?php

namespace Nestor\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class ProjectStatuses extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'id',
        'name',
        'description'
    ];

    protected $hidden = [];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'project_statuses';

}

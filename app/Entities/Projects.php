<?php

namespace Nestor\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Projects extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['name', 'description', 'project_statuses_id', 'created_by'];

    protected $hidden = [];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'projects';

}

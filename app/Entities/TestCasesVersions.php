<?php

namespace Nestor\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class TestCasesVersions extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['version', 'test_case_id', 'execution_type_id', 'name', 'prerequisite',' description'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'test_cases_versions';
}

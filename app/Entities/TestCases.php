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

    public function project()
    {
        return $this->belongsTo('Nestor\\Entities\\Projects', 'project_id');
    }

    public function testSuite()
    {
        return $this->belongsTo('Nestor\\Entities\\TestSuites', 'test_suite_id');
    }

    public function testCaseVersions()
    {
        return $this->hasMany('Nestor\\Entities\\TestCaseVersions', 'test_case_id');
    }

    public function latestVersion()
    {
        return $this->hasMany('Nestor\\Entities\\TestCaseVersions', 'test_case_id')
            ->orderBy('version', 'desc')
            ->take(1)
            ->firstOrFail(); // FIXME: redundant take1?
    }

    // public function steps()
    // {
    //     return $this->hasManyThrough('Nestor\\Model\\TestCaseStepVersion', 'Nestor\\Model\\TestCaseVersion', 'test_case_id', 'test_case_version_id');
    // }
}

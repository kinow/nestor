<?php

namespace Nestor\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class TestCasesVersions extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['version', 'test_case_id', 'execution_type_id', 'name', 'prerequisite','description'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'test_cases_versions';

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
    }

    public function testcase()
    {
        return $this->belongsTo('Nestor\\Entities\\TestCases', 'test_case_id');
    }

    public function executionType()
    {
        return $this->belongsTo('Nestor\\Entities\\ExecutionTypes', 'execution_type_id');
    }

    // public function executions()
    // {
    //     return $this->hasMany('Nestor\\Entities\\Executions', 'test_case_id');
    // }

    // public function steps()
    // {
    //     return $this->belongsToMany('Nestor\\Entities\\TestCaseSteps', 'test_case_step_versions');
    // }

    // public function sortedSteps()
    // {
    //     return TestCaseVersion::
    //         hasMany('Nestor\\Entities\\TestCaseStepVersion', 'test_case_version_id', 'id');
    // }

    // public function labels()
    // {
    //     return $this->belongsToMany('Nestor\\Entities\\Label', 'test_case_versions_labels', 'test_case_version_id')->withTimestamps();
    // }

    // public function testplans()
    // {
    //     return $this->belongsToMany('Nestor\\Entities\\TestPlan', 'test_plans_test_cases', 'test_case_version_id', 'test_plan_id')
    //         ->withPivot('assignee')
    //         ->withTimestamps();
    // }

    // public function assignee()
    // {
    //     return $this->pivot->assignee;
    // }
}

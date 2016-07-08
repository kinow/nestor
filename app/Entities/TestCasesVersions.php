<?php
/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 Bruno P. Kinoshita, Peter Florijn
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Nestor\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class TestCasesVersions extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['version', 'test_case_id', 'execution_type_id', 'name', 'prerequisite', 'description'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'test_cases_versions';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getVersionAttribute($value)
    {
        return intval($value);
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

    public function testplans()
    {
        return $this->belongsToMany('Nestor\\Entities\\TestPlans', 'test_plans_test_cases', 'test_case_version_id', 'test_plan_id')
            ->withPivot('assignee')
            ->withTimestamps();
    }

    // public function assignee()
    // {
    //     return $this->pivot->assignee;
    // }
}

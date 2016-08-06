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

class TestRuns extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['test_plan_id', 'name', 'description'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'test_runs';

    protected $appends = ['progress'];

    public function testPlan()
    {
        return $this->belongsTo('Nestor\\Entities\\TestPlans', 'test_plan_id');
    }

    public function executions()
    {
        return $this->hasMany('Nestor\\Entities\\Executions', 'test_run_id');
    }

    public function countTestCases()
    {
        return static::join('test_plans', 'test_plans.id', '=', 'test_runs.test_plan_id')
            ->join('test_plans_test_cases', 'test_plans_test_cases.test_plan_id', '=', 'test_plans.id')
            ->join('test_cases_versions', 'test_cases_versions.id', '=', 'test_plans_test_cases.test_case_version_id')
            ->join('test_cases', 'test_cases.id', '=', 'test_cases_versions.test_case_id')
            ->where('test_runs.id', '=', $this->id)
            ->count('test_cases.id');
    }

    public function getProgressAttribute()
    {
        $percentage = 0;
        $progress = array();
        $total = $this->countTestCases();
        $executions = Executions::select('executions.*')
            ->where('executions.test_run_id', $this->id)
            ->join('test_cases_versions', 'test_cases_versions.id', '=', 'executions.test_case_version_id')
            ->join('test_cases', 'test_cases.id', '=', 'test_cases_versions.test_case_id')
            ->groupBy('test_cases.id')
            ->get()
        ;

        $executionStatuses = ExecutionStatuses::all();
        $executionStatusesCount = array();
        foreach ($executionStatuses as $executionStatus) {
            $executionStatusesCount[$executionStatus->id] = [
                'name' => $executionStatus->name,
                'value' => 0
            ];
        }
        foreach ($executions as $execution) {
            $executionStatusesCount[$execution->execution_status_id]['value'] += 1;
        }
        $executionStatusesCount[1]['value'] = count($executionStatusesCount) - $total;
        foreach ($executionStatusesCount as $statusId => $entry) {
            $count = $entry['value'];
            $value = $total ? ($count / $total) * 100 : 0;
            $entry['value'] = $value;
            $progress[$statusId] = $entry;
        }
        $percentage = $total ? ($executions->count()/$total) * 100 : 0;
        return array(
            'percentage' => $percentage,
            'progress' => $progress
        );
    }
}

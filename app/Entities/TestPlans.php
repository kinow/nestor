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

class TestPlans extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['project_id', 'name', 'description'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'test_plans';

    public function project()
    {
        return $this->belongsTo('Nestor\\Entities\\Project', 'project_id');
    }

    public function testCases()
    {
        return $this
            ->belongsToMany('Nestor\\Entities\\TestCaseVersion', 'test_plans_test_cases')
            ->orderBy('version', 'desc')
            ->with('executionType')
            ->withTimestamps()
        ;
    }

    // public function testRuns()
    // {
    //     return $this->hasMany('Nestor\\Entities\\TestRun');
    // }

//     public function testcasesDetached()
//     {
//         $sql = <<<EOF
// select tc.*, tcv.version 
// from test_cases tc 
// inner join test_case_versions tcv on tc.id = tcv.test_case_id 
// inner join test_plans_test_cases tptc on tptc.test_case_version_id = tcv.id 
// where tptc.test_plan_id = :test_plan_id 
// group by tc.id 
// EOF;
//         $results = DB::select(DB::raw($sql), array('test_plan_id' => $this->id));
//         return $results;
//         // $collection = new \Illuminate\Database\Eloquent\Collection();
//         // foreach ($results as $rawObject)
//         // {
//         //      $model = new Model();
//         //      $collection->add($model->newFromBuilder($rawObject));
//         // }
//         // return $collection;
//     }

    // public function testcases()
    // {
    //  $testcases = TestCase2::
    //      select('test_cases.*')
    //      ->join('test_case_versions', 'test_case_versions.test_case_id', '=', 'test_cases.id')
    //      ->join('test_plans_test_cases', 'test_plans_test_cases.test_case_version_id', '=', 'test_case_versions.id')
    //      ->where('test_plans_test_cases.test_plan_id', '=', $this->id)
    //      ->groupBy('test_cases.id');

    //  return $testcases;
    // }

    // public function testcases()
    // {
    //  $testcases = array();
    //  $testcaseVersions = $this->testcaseVersions()->get();
    //  foreach ($testcaseVersions as $testcaseVersion)
    //  {
    //      $testcases[] = $testcaseVersion->testcase()->first();
    //  }
    //  return new \Illuminate\Support\Collection($testcases);
    // }

    // public function testcaseVersions()
    // {
    //  $testcases = TestCaseVersion::
    //      select('test_case_versions.*')
    //      ->join('test_plans_test_cases', 'test_plans_test_cases.test_case_version_id', '=', 'test_case_versions.id')
    //      ->where('test_plans_test_cases.test_plan_id', '=', $this->id)
    //      ->groupBy('test_case_versions.test_case_id');

    //  return $testcases;
    // }

    // FIXME: same as testcases() ?
    // public function testcaseVersions()
    // {
    //  return $this->belongsToMany('TestCaseVersion', 'test_plans_test_cases', 'test_plan_id', 'test_case_version_id')
    //      ->withPivot('assignee')
    //      ->withTimestamps();
    // }

    // public function hasExecutions()
    // {
    //     return TestPlan::join('test_runs', 'test_runs.test_plan_id', '=', $this->id)
    //         ->join('executions', 'executions.test_run_id', '=', 'test_runs.id')
    //         ->count('test_plans.id') > 0;
    // }
}

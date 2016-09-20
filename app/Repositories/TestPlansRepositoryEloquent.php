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

namespace Nestor\Repositories;

use Illuminate\Container\Container as Application;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Nestor\Repositories\TestPlansRepository;
use Nestor\Repositories\TestCasesRepository;
use Nestor\Entities\TestPlans;
use Nestor\Validators\TestPlansValidator;

/**
 * Class TestPlansRepositoryEloquent
 * @package namespace Nestor\Repositories;
 */
class TestPlansRepositoryEloquent extends BaseRepository implements TestPlansRepository
{

    /**
     *
     * @var TestCasesRepository $testCasesRepository
     */
    protected $testCasesRepository;

    /**
     *
     * @param Application $app
     * @param TestCasesRepository $testCasesRepository
     */
    public function __construct(Application $app, TestCasesRepository $testCasesRepository)
    {
        parent::__construct($app);
        $this->testCasesRepository = $testCasesRepository;
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TestPlans::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Create simple test plan report.
     *
     * @param int $testPlanId
     * @return mixed Array
     */
    public function createSimpleTestPlanReport($testPlanId)
    {
        $testCasesSummary = $this->testCasesRepository->scopeQuery(function ($query) use ($testPlanId) {
            return $query
                ->select('test_cases_versions.*')
                ->join('test_cases_versions', 'test_cases.id', '=', 'test_cases_versions.test_cases_id')
                ->join('test_plans_test_cases', 'test_cases_versions.id', '=', 'test_plans_test_cases.test_cases_versions_id')
                ->join('test_plans', 'test_plans_test_cases.test_plan_id', '=', 'test_plans.id')
                ->where('test_plans.id', $testPlanId)
            ;
        })->with('executions')->all();

        $testCasesSummaryArray = $testCasesSummary->toArray();

        foreach ($testCasesSummaryArray as &$entry) {
            $executions = $entry['executions'];
            if (isset($executions) && !empty($executions)) {
                usort($executions, function ($a, $b) {
                    return $a['id'] < $b['id'];
                });
                unset($entry['executions']);
                $entry['latest_execution'] = $executions[0];
            }
        }

        return [
            'test_cases' => $testCasesSummaryArray
        ];
    }
}

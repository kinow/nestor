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

namespace Nestor\Http\Controllers;

use \Validator;

use Illuminate\Http\Request;

use Dingo\Api\Routing\Helpers as DingoApiHelpers;

use Nestor\Http\Controllers\Controller;
use Nestor\Repositories\ProjectsRepository;
use Nestor\Repositories\TestPlansRepository;

class ReportsController extends Controller
{
    use DingoApiHelpers;

    /**
     * @var ProjectsRepository
     */
    protected $projectsRepository;

    /**
     * @var TestPlansRepository
     */
    protected $testPlansRepository;

    public function __construct(ProjectsRepository $projectsRepository, TestPlansRepository $testPlansRepository)
    {
        $this->projectsRepository = $projectsRepository;
        $this->testPlansRepository = $testPlansRepository;
    }

    /**
     * Project simple report: list number of test plans, number of test suites,
     * number of test cases, number of test runs, number of test cases executed,
     * % of each execution statuses.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $projectId
     * @return \Illuminate\Http\Response
     */
    public function simpleProjectReport(Request $request, $projectId)
    {
        $payload = ['projectId' => $projectId];
        $validator = Validator::make($payload, [
            'projectId' => 'required|integer|min:1'
        ]);
        
        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }
        $report = $this->projectsRepository->createSimpleProjectReport($projectId);
        return response()->json($report);
    }

    /**
     * Test Plan testing report: first user selects a test plan, then he gets
     * the latest execution status for each test case in the test plan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $testPlanId
     * @return \Illuminate\Http\Response
     */
    public function simpleTestPlanReport(Request $request, $testPlanId)
    {
        $payload = ['testPlanId' => $testPlanId];
        $validator = Validator::make($payload, [
            'testPlanId' => 'required|integer|min:1'
        ]);
        
        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }
        $report = $this->testPlansRepository->createSimpleTestPlanReport($testPlanId);
        return response()->json($report);
    }
}

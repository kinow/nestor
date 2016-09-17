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

use Log;
use Parsedown;
use Validator;

use Illuminate\Http\Request;

use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

use Nestor\Http\Requests;
use Nestor\Repositories\ExecutionsRepository;
use Nestor\Repositories\TestCasesRepository;
use Nestor\Validators\ExecutionsValidator;

class ExecutionsController extends Controller
{

    /**
     * @var ExecutionsRepository
     */
    protected $executionsRepository;

    /**
     *
     * @var TestCasesRepository $testCasesRepository
     */
    protected $testCasesRepository;

    public function __construct(ExecutionsRepository $executionsRepository, TestCasesRepository $testCasesRepository)
    {
        $this->executionsRepository = $executionsRepository;
        $this->testCasesRepository = $testCasesRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Log::debug("Returning paginated executions");
        $testPlanId = $request->input('project_id', -1);
        return $this->executionsRepository->scopeQuery(function ($query) use ($testPlanId) {
            return $query->where('test_plan_id', $testPlanId)->orderBy('name', 'ASC');
        })->paginate();
    }

    public function showTestCase($testPlanId, $testRunId, $testSuiteId, $id)
    {
        $testCase = $this->testCasesRepository->findTestCaseWithVersionAndExecutions($id, $testRunId);
        $testCase['formatted_description'] = Parsedown::instance()->text($testCase['version']['description']);
        $testCase['formatted_prerequisite'] = Parsedown::instance()->text($testCase['version']['prerequisite']);
        return $testCase;
    }

    public function executeTestCase(Request $request, $testPlanId, $testRunId, $testsuiteId, $testcaseId)
    {
        Log::debug("Executing test case");

        // Users MUST NOT be allowed to set a test case execution to NOT RUN. It is
        // that way by default. This comma separated list of this array contains the invalid values.
        $illegalExecutionstatusesIds = join(',', [1]);
        Log::debug(sprintf("Illegal characters: %s", $illegalExecutionstatusesIds));

        $payload = $request->only('notes', 'execution_statuses_id');
        $validator = Validator::make($payload, [
            'notes' => 'max:1000',
            'execution_statuses_id' => sprintf('required|integer|min:1|not_in:%s', $illegalExecutionstatusesIds)
        ]);
        
        if ($validator->fails()) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Failed to execute test case', $validator->errors());
        }
        
        // FIXME: find the test case version ID
        $entity = $this->executionsRepository->execute($payload['execution_statuses_id'], $payload['notes'], $testRunId, $testcaseId);
        
        return $entity;
    }
}

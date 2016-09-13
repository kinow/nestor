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

use Illuminate\Http\Request;
use Log;
use Parsedown;
use Validator;
use Nestor\Http\Requests;
use Nestor\Entities\NavigationTree;
use Nestor\Http\Controllers\Controller;
use Nestor\Repositories\TestCasesRepository;

/**
 * Project resource representation.
 *
 * @Resource("TestCases", uri="projects/{projectId}/testsuites/{testsuiteId}/testcases")
 */
class TestCasesController extends Controller
{

    /**
     *
     * @var TestCasesRepository $testCasesRepository
     */
    protected $testCasesRepository;
    public function __construct(TestCasesRepository $testCasesRepository)
    {
        $this->testCasesRepository = $testCasesRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $projectId project ID
     * @param int $testsuiteId test suite ID
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $projectId, $testsuiteId)
    {
        Log::debug("Creating a test case");
        $testcaseAttributes = ['project_id' => $projectId, 'test_suite_id' => $testsuiteId];
        $testcaseAttributesValidator = Validator::make($testcaseAttributes, [
            'project_id' => 'required|integer|min:1',
            'test_suite_id' => 'required|integer|min:1'
        ]);
        if ($testcaseAttributesValidator->fails()) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not create new test case.', $testcaseAttributesValidator->errors());
        }

        Log::debug("Creating a test case version");
        $testcaseVersionAttributes = $request->only('execution_type_id', 'name', 'prerequisite', 'description');
        $testcaseVersionAttributesValidator = Validator::make($testcaseVersionAttributes, [
            'execution_type_id' => 'required|integer|min:1',
            'name' => 'required|min:1|max:255',
            'prerequisite' => 'max:1000',
            'description' => 'max:1000'
        ]);
        if ($testcaseVersionAttributesValidator->fails()) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not create new test case version.', $testcaseVersionAttributesValidator->errors());
        }
        
        $ancestorNodeId = NavigationTree::testsuiteId($testsuiteId);
        Log::debug("Ancestor ID: " . $ancestorNodeId);

        $testcaseVersionAttributes['version'] = 1;

        $entity = $this->testCasesRepository->createWithAncestor($testcaseAttributes, $testcaseVersionAttributes, $ancestorNodeId);
        return $entity;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($projectId, $testSuiteId, $id)
    {
        // TBD: should we use projectId here too?
        $testCase = $this->testCasesRepository->findTestCaseWithVersion($id);
        $testCase['formatted_description'] = Parsedown::instance()->text($testCase['version']['description']);
        $testCase['formatted_prerequisite'] = Parsedown::instance()->text($testCase['version']['prerequisite']);
        return $testCase;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $projectId project ID
     * @param  int  $testsuiteId test suite ID
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $projectId, $testsuiteId, $id)
    {
        Log::debug("Updating an existing test case");
        $testcaseVersionAttributes = $request->only('test_cases_id', 'execution_type_id', 'name', 'prerequisite', 'description');
        $testcaseVersionAttributesValidator = Validator::make($testcaseVersionAttributes, [
            'test_cases_id' => 'required|integer|min:1',
            'execution_type_id' => 'required|integer|min:1',
            'name' => 'required|min:1|max:255',
            'prerequisite' => 'max:1000',
            'description' => 'max:1000'
        ]);
        if ($testcaseVersionAttributesValidator->fails()) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not update test case version.', $testcaseVersionAttributesValidator->errors());
        }

        $ancestorNodeId = NavigationTree::testsuiteId($testsuiteId);
        Log::debug("Ancestor ID: " . $ancestorNodeId);

        $testcaseVersionAttributes['version'] = 1;

        $entity = $this->testCasesRepository->updateWithAncestor($testcaseVersionAttributes, $ancestorNodeId);
        return $entity;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $projectId project ID
     * @param  int  $testSuiteId test suite ID
     * @param  int  $id test case ID
     * @return \Illuminate\Http\Response
     */
    public function destroy($projectId, $testSuiteId, $id)
    {
        return array (
            'Result' => $this->testCasesRepository->delete($id)
        );
    }
}

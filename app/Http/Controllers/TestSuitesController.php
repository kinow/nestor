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
use Nestor\Entities\NavigationTree;
use Nestor\Http\Controllers\Controller;
use Nestor\Repositories\TestSuitesRepository;
use Parsedown;
use Validator;

/**
 * Test Suite resource representation.
 *
 * @Resource("Test Suites", uri="/testsuites")
 */
class TestSuitesController extends Controller
{
    
    /**
     *
     * @var TestSuitesRepository $testSuitesRepository
     */
    protected $testSuitesRepository;
    public function __construct(TestSuitesRepository $testSuitesRepository)
    {
        $this->testSuitesRepository = $testSuitesRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $projectId Test suite's project ID
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $projectId)
    {
        Log::debug("Creating a test suite");
        $payload = $request->only('name', 'description', 'project_id', 'parent_id', 'created_by');
        $validator = Validator::make($payload, [
                'name' => 'required|max:255|unique:projects',
                'description' => 'max:1000',
                'project_id' => 'required|integer|min:1',
                'parent_id' => 'required|integer|min:0',
                'created_by' => 'required|integer|min:1'
        ]);
        
        if ($validator->fails()) {
            Log::debug('Test suite validation error: ' . $validator->errors());
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not create new test suite.', $validator->errors());
        }
        
        $ancestorNodeId = NavigationTree::projectId($payload ['project_id']);
        $parentId = (int) $request->get('parent_id', 0);
        if ($parentId > 0) {
            $ancestorNodeId = NavigationTree::testSuiteId($parentId);
        }
        
        Log::debug("Ancestor ID: " . $ancestorNodeId);
        
        $entity = $this->testSuitesRepository->createWithAncestor($payload, $ancestorNodeId);
        
        return $entity;
    }
    
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($projectId, $id)
    {
        // TBD: should we use projectId here too?
        $testSuite = $this->testSuitesRepository->find($id);
        $testSuite->formatted_description = Parsedown::instance()->text($testSuite->description);
        return $testSuite;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $projectId, $id)
    {
        Log::debug("Updating an existing test suite");
        $payload = $request->only('name', 'description');
        $validator = Validator::make($payload, [
                'name' => 'required|max:255',
                'description' => 'max:1000'
        ]);

        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }

        $entity = $this->testSuitesRepository->update($payload, $id);
        return $entity;
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param int $projectId
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($projectId, $id)
    {
        return array (
            'Result' => $this->testSuitesRepository->delete($id)
        );
    }
}

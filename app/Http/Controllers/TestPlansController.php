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
use Nestor\Http\Controllers\Controller;
use Nestor\Repositories\TestPlansRepository;
use Parsedown;
use Validator;

/**
 * Test Plan resource representation.
 *
 * @Resource("Test Plans", uri="/testplans")
 */
class TestPlansController extends Controller
{
    
    /**
     *
     * @var TestPlansRepository $testPlansRepository
     */
    protected $testPlansRepository;
    
    /**
     *
     * @param TestPlansRepository $testPlansRepository
     */
    public function __construct(TestPlansRepository $testPlansRepository)
    {
        $this->testPlansRepository = $testPlansRepository;
    }
    
    /**
     * Show all test plans.
     *
     * @return array @Get("/")
     *         @Versions({"v1"})
     *         @Request({})
     *         @Response(200, body={"id": 1, "name": "test plan name", "description": "test plan description", "project_id": "test plan project ID"})
     */
    public function index(Request $request)
    {
        Log::debug("Returning paginated test plans");
        $projectId = $request->input('project_id', -1);
        return $this->testPlansRepository->scopeQuery(function ($query) use ($projectId) {
            return $query->where('project_id', $projectId)->orderBy('name', 'ASC');
        })->paginate();
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // TODO: throw not implemented
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::debug("Creating a test plan");
        $payload = $request->only('name', 'description', 'project_id');
        $validator = Validator::make($payload, [
            'name' => 'required|max:255',
            'description' => 'max:1000',
            'project_id' => 'required|integer|min:1'
        ]);
        
        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }
        
        $entity = $this->testPlansRepository->create($payload);
        
        return $entity;
    }
    
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $testPlan = $this->testPlansRepository->with('testCases')->find($id);
        $testPlan->formatted_description = Parsedown::instance()->text($testPlan->description);
        return $testPlan;
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Log::debug("Updating an existing test plan");
        $payload = $request->only('project_id', 'name', 'description');
        $validator = Validator::make($payload, [
            'project_id' => 'min:1|integer',
            'name' => 'required|max:255',
            'description' => 'max:1000'
        ]);

        if ($validator->fails()) {
            Log::debug('Validation failed while updating test plan: ' . $validator->errors());
            $this->throwValidationException($request, $validator);
        }

        $entity = $this->testPlansRepository->update($payload, $id);
        return $entity;
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return array (
            'Result' => $this->testPlansRepository->delete($id)
        );
    }

    public function storeTestCases(Request $request, $id)
    {
        $testPlan = $this->testPlansRepository->with('testCases')->find($id);
        $existingTestcaseVersions = $testPlan['test_cases'];
        $nodesSelected = array();
        $testcases = array();
        foreach ($_POST as $entry => $value) {
            if (strpos($entry, 'ft_') === 0 && strpos($entry, 'ft_1_active') !== 0) {
                if (is_array($value)) {
                    foreach ($value as $tempValue) {
                        $nodesSelected[] = $tempValue;
                    }
                } else {
                    $nodesSelected[] = $value;
                }
            }
        }
        foreach ($nodesSelected as $node) {
            $children = HMVC::get("api/v1/nodes/$node");
            $this->getTestCasesFrom($children, $testcases);
        }
        
        // What to remove?
        $testcasesForRemoval = array();
        foreach ($existingTestcaseVersions as $existing) {
            $found = false;
            foreach ($testcases as $testcase) {
                if ($existing['test_case_id'] == $testcase['id']) {
                    $found = true;
                }
            }
            if (!$found) {
                $testcasesForRemoval[] = $existing;
            }
        }

        // What do add?
        $testcasesForAdding = array();
        foreach ($testcases as $testcase) {
            $found = false;
            foreach ($existingTestcaseVersions as $existing) {
                if ($existing['test_case_id'] == $testcase['id']) {
                    $found = true;
                }
            }
            if (!$found) {
                $testcasesForAdding[] = $testcase['version'];
            }
        }

        // FIXME: bulk operations

        foreach ($testcasesForAdding as $addMe) {
            Log::info(sprintf('Adding testcase %s version %s to test plan %s', $addMe['name'], $addMe['version'], $testPlan['name']));
            $testCaseVersionid = $addMe['id'];
            HMVC::post("api/v1/testplans/$id/testcases/$testCaseVersionid");
        }

        foreach ($testcasesForRemoval as $removeMe) {
            Log::info(sprintf('Removing test case %s version %s from test plan %s', $removeMe['name'], $removeMe['version'], $testPlan['name']));
            $testCaseVersionid = $removeMe['id'];
            HMVC::delete("api/v1/testplans/$id/testcases/$testCaseVersionid");
        }
    }
}

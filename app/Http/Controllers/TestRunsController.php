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
use Nestor\Repositories\TestRunsRepository;

class TestRunsController extends Controller
{

    /**
     * @var testRunsRepository
     */
    protected $testRunsRepository;

    public function __construct(TestRunsRepository $testRunsRepository)
    {
        $this->testRunsRepository = $testRunsRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Log::debug("Returning paginated test runs");
        $testPlanId = $request->input('test_plan_id', -1);
        return $this->testRunsRepository->scopeQuery(function ($query) use ($testPlanId) {
            return $query->where('test_plan_id', $testPlanId)->orderBy('name', 'ASC');
        })->paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::debug("Creating a test run");
        $payload = $request->only('name', 'description', 'test_plan_id');
        $validator = Validator::make($payload, [
            'name' => 'required|max:255',
            'description' => 'max:1000',
            'test_plan_id' => 'required|integer|min:1'
        ]);
        
        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }
        
        $entity = $this->testRunsRepository->create($payload);
        
        return $entity;
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($testPlanId, $id)
    {
        $testRun = $this->testRunsRepository->find($id);
        $testRun->formatted_description = Parsedown::instance()->text($testRun->description);
        return $testRun;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(Request $request, $testPlanId, $id)
    {
        Log::debug("Updating an existing test run");
        $payload = $request->only('name', 'description');
        $payload['test_plan_id'] = $testPlanId;
        $validator = Validator::make($payload, [
            'test_plan_id' => 'min:1|integer',
            'name' => 'required|max:255',
            'description' => 'max:1000'
        ]);

        if ($validator->fails()) {
            Log::debug('Validation failed while updating test run: ' . $validator->errors());
            $this->throwValidationException($request, $validator);
        }

        $entity = $this->testRunsRepository->update($payload, $id);
        return $entity;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($testPlanId, $id)
    {
        return array (
            'Result' => $this->testRunsRepository->delete($id)
        );
    }
}

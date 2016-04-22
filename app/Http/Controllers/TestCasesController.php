<?php

namespace Nestor\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Parsedown;
use Validator;
use Nestor\Http\Requests;
use Nestor\Entities\NavigationTree;
use Nestor\Http\Controllers\Controller;
use Nestor\Repositories\TestCasesRepository;

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function show($id)
    {
        // TBD: should we use projectId here too?
        $testCase = $this->testCasesRepository->find($id);
        $testCase['formatted_description'] = Parsedown::instance()->text($testCase['version']['description']);
        $testCase['formatted_prerequisite'] = Parsedown::instance()->text($testCase['version']['prerequisite']);
        return $testCase;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

<?php

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return [ 
                [ 
                        'id' => 10,
                        'name' => 'Suite 001',
                        'description' => 'Test suite 003' 
                ],
                [ 
                        'id' => 20,
                        'name' => 'Suite 002',
                        'description' => 'Test suite 002' 
                ] 
        ];
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
     * @param int $projectId Test suite's project ID         
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $projectId)
    {
        Log::debug("Creating a test suite");
        $payload = $request->only('name', 'description', 'project_id', 'created_by');
        $validator = Validator::make($payload, [ 
                'name' => 'required|max:255|unique:projects',
                'description' => 'max:1000',
                'project_id' => 'required|integer|min:1',
                'created_by' => 'required|integer|min:1' 
        ]);
        
        if ($validator->fails())
        {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not create new test suite.', $validator->errors());
            //$this->throwValidationException($request, $validator);
        }
        
        $ancestorNodeId = NavigationTree::projectId($payload ['project_id']);
        $parentId = (int) $request->get('parent_id', 0);
        if ($parentId > 0)
        {
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
    public function update(Request $request, $projectId, $id)
    {
        Log::debug("Updating an existing test suite");
        $payload = $request->only('name', 'description');
        $validator = Validator::make($payload, [ 
                'name' => 'required|max:255',
                'description' => 'max:1000' 
        ]);

        if ($validator->fails())
        {
            $this->throwValidationException($request, $validator);
        }

        $entity = $this->testSuitesRepository->update($payload, $id);
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
                'Result' => $this->testSuitesRepository->delete($id) 
        );
    }
}

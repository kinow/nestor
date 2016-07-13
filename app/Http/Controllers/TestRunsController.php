<?php

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
    public function show($id)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

<?php

namespace Nestor\Http\Controllers;

use Log;

use Illuminate\Http\Request;

use Nestor\Http\Requests;
use Nestor\Http\Controllers\Controller;
use Nestor\Repositories\ExecutionTypesRepository;

class ExecutionTypesController extends Controller
{

    /**
     *
     * @var ExecutionTypesRepository $executionTypesRepository
     */
    protected $executionTypesRepository;

    /**
     *
     * @param ExecutionTypesRepository $projectsRepository
     */
    public function __construct(ExecutionTypesRepository $executionTypesRepository)
    {
        $this->executionTypesRepository = $executionTypesRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::debug("Returning all execution types");
        return $this->executionTypesRepository->all();
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

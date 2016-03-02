<?php

namespace Nestor\Http\Controllers;

use Illuminate\Http\Request;

use Nestor\Http\Controllers\Controller;

/**
 * Test Suite resource representation.
 *
 * @Resource("Test Suites", uri="/testsuites")
 */
class TestSuitesController extends Controller
{
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
    public function show($projectId, $id)
    {
        return [
                'id' => (int) $id,
                'name' => sprintf('Test suite 00%d', $id),
                'description' => sprintf('Le description du test suite %d', $id)
            ];
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

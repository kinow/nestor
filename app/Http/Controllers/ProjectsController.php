<?php

namespace Nestor\Http\Controllers;

use Illuminate\Http\Request;

use Nestor\Http\Requests;
use Nestor\Http\Controllers\Controller;

/**
 * Project resource representation.
 *
 * @Resource("Projects", uri="/projects")
 */
class ProjectsController extends Controller
{
    /**
     * Show all users.
     * @return array
     * @Get("/")
     * @Versions({"v1"})
     * @Request({})
     * @Response(200, body={"id": 1, "name": "project name"})
     */
    public function index()
    {
        return [
            [
                'id' => 1,
                'name' => 'Test Project',
            ],
            [
                'id' => 2,
                'name' => 'Dummy Project'
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
    public function show($id)
    {
        return [
                'id' => (int) $id,
                'name' => 'Test Project',
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

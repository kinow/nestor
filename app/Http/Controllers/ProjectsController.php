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
use Nestor\Repositories\ProjectsRepository;
use Parsedown;
use Validator;

/**
 * Project resource representation.
 *
 * @Resource("Projects", uri="/projects")
 */
class ProjectsController extends Controller
{
    
    /**
     *
     * @var ProjectsRepository $projectsRepository
     */
    protected $projectsRepository;
    
    /**
     *
     * @param ProjectsRepository $projectsRepository
     */
    public function __construct(ProjectsRepository $projectsRepository)
    {
        $this->projectsRepository = $projectsRepository;
    }
    
    /**
     * Show all projects.
     *
     * @return array @Get("/")
     *         @Versions({"v1"})
     *         @Request({})
     *         @Response(200, body={"id": 1, "name": "project name", "url": "http://<host>:<port>/<path>", "description": "project description"})
     */
    public function index()
    {
        Log::debug("Returning paginated projects");
        return $this->projectsRepository->scopeQuery(function ($query) {
            return $query->orderBy('name', 'ASC');
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
        Log::debug("Creating a project");
        $payload = $request->only('name', 'description', 'project_statuses_id', 'created_by');
        $validator = Validator::make($payload, [
                'name' => 'required|max:255|unique:projects',
                'description' => 'max:1000',
                'created_by' => 'required|integer|min:1'
        ]);
        
        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }
        
        $entity = $this->projectsRepository->create($payload);
        
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
        $project = $this->projectsRepository->find($id);
        $project->formatted_description = Parsedown::instance()->text($project->description);
        return $project;
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
        Log::debug("Updating an existing project");
        $payload = $request->only('name', 'description');
        $validator = Validator::make($payload, [
                'name' => 'required|max:255',
                'description' => 'max:1000'
        ]);

        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }

        $entity = $this->projectsRepository->update($payload, $id);
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
            'Result' => $this->projectsRepository->delete($id)
        );
    }

    public function position(Request $request, $projectId)
    {
        if ($projectId <= 0) {
            $request->session()->remove('project_id');
            return null; // TODO what should be returned here? true? false? nothing?
        } else {
            $project = $this->projectsRepository->find($projectId);
            $request->session()->put('project_id', $project->id);
            $project->formatted_description = Parsedown::instance()->text($project->description);
            return $project;
        }
    }
}

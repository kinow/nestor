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
     * @Get("/{?page}")
     * @Versions({"v1"})
     * @Request("")
     * @Response(200, body={"total":2,"per_page":15,"current_page":1,"last_page":1,"next_page_url":null,"prev_page_url":null,"from":1,"to":15,"data": {{"id":1,"project_statuses_id":"1","name":"Project A","description":"# Project A\n\nThis is the **project A**","created_by":"1","created_at":"2016-11-12 12:00:56","updated_at":"2016-11-12 12:00:56"},{"id":2,"project_statuses_id":"1","name":"Project B","description":"# Project B\n\nThis is the **project B**","created_by":"1","created_at":"2016-11-12 12:00:56","updated_at":"2016-11-12 12:00:56"}}})
     *
     * return array
     */
    public function index()
    {
        Log::debug("Returning paginated projects");
        return $this->projectsRepository->scopeQuery(function ($query) {
            return $query->orderBy('id', 'ASC');
        })->paginate();
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
            return []; // TODO what should be returned here? true? false? nothing?
        } else {
            $project = $this->projectsRepository->find($projectId);
            $request->session()->put('project_id', $project->id);
            $project->formatted_description = Parsedown::instance()->text($project->description);
            return $project;
        }
    }
}

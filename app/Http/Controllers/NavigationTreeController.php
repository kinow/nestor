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
use Nestor\Http\Controllers\Controller;
use Nestor\Repositories\NavigationTreeRepository;
use Nestor\Repositories\ProjectsRepository;
use Nestor\Util\NavigationTreeUtil;

class NavigationTreeController extends Controller
{
    
    /**
     * @var NavigationTreeRepository $navigationTreeRepository
     */
    protected $navigationTreeRepository;

    /**
     * @var ProjectsRepository $projectsRepository
     */
    protected $projectsRepository;

    /**
     * @param NavigationTreeRepository $navigationTreeRepository
     * @param ProjectsRepository $projectsRepository
     */
    public function __construct(NavigationTreeRepository $navigationTreeRepository, ProjectsRepository $projectsRepository)
    {
        $this->navigationTreeRepository = $navigationTreeRepository;
        $this->projectsRepository = $projectsRepository;
    }
    
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $defaultLength = 1;
        $length = $request->get('length', $defaultLength);
        // FIXME: validate the default length parameter

        $projectId = $request->session()->get('project_id');
        // FIXME: validate the project ID

        $nodes = $this->navigationTreeRepository->children($id, $length)->toArray();
        $executedTestCaseVersionIds = $this->projectsRepository->getExecutedTestCaseVersionIds($projectId)->toArray();
        $tree = NavigationTreeUtil::createNavigationTree($nodes, $id, $executedTestCaseVersionIds);
        return $tree;
    }

    public function move(Request $request)
    {
        $descendant = $request->get('descendant');
        $ancestor = $request->get('ancestor');

        // TODO: use a Validator here later
        if ($ancestor && $descendant) {
            $node = $this->navigationTreeRepository->move($descendant, $ancestor);
            return $node;
        }
    }
}

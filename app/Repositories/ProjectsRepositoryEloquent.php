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

namespace Nestor\Repositories;

use DB;
use Exception;
use Illuminate\Container\Container as Application;
use Log;
use Nestor\Entities\NavigationTree;
use Nestor\Entities\Projects;
use Nestor\Entities\ExecutionStatuses;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Nestor\Repositories\NavigationTreeRepository;
use Nestor\Repositories\TestPlansRepository;
use Nestor\Repositories\TestSuitesRepository;
use Nestor\Repositories\TestCasesRepository;
use Nestor\Repositories\TestRunsRepository;
use Prettus\Repository\Events\RepositoryEntityCreated;
use Prettus\Repository\Events\RepositoryEntityDeleted;
use Prettus\Repository\Events\RepositoryEntityUpdated;

/**
 * Class ProjectsRepositoryEloquent
 *
 * @package namespace Nestor\Repositories;
 */
class ProjectsRepositoryEloquent extends BaseRepository implements ProjectsRepository
{
    
    /**
     *
     * @var NavigationTreeRepository $navigationTreeRepository
     */
    protected $navigationTreeRepository;

    /**
     *
     * @var TestPlansRepository $testPlansRepository
     */
    protected $testPlansRepository;

    /**
     *
     * @var TestSuitesRepository $testSuitesRepository
     */
    protected $testSuitesRepository;

    /**
     *
     * @var TestCasesRepository $testCasesRepository
     */
    protected $testCasesRepository;
    
    /**
     *
     * @var TestRunsRepository $testRunsRepository
     */
    protected $testRunsRepository;

    /**
     *
     * @var ExecutionsRepository $executionsRepository
     */
    protected $executionsRepository;

    /**
     *
     * @param Application $app
     * @param NavigationTreeRepository $navigationTreeRepository
     * @param TestPlansRepository $testPlansRepository
     * @param TestCasesRepository $testCasesRepository
     * @param TestRunsRepository $testRunsRepository
     * @param ExecutionsRepository $executionsRepository
     */
    public function __construct(Application $app, NavigationTreeRepository $navigationTreeRepository, TestPlansRepository $testPlansRepository, TestSuitesRepository $testSuitesRepository, TestCasesRepository $testCasesRepository, TestRunsRepository $testRunsRepository, ExecutionsRepository $executionsRepository)
    {
        parent::__construct($app);
        $this->navigationTreeRepository = $navigationTreeRepository;
        $this->testPlansRepository = $testPlansRepository;
        $this->testSuitesRepository = $testSuitesRepository;
        $this->testCasesRepository = $testCasesRepository;
        $this->testRunsRepository = $testRunsRepository;
        $this->executionsRepository = $executionsRepository;
    }
    
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Projects::class;
    }
    
    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
    /**
     * Save a new entity in repository
     *
     * @throws ValidatorException
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        DB::beginTransaction();
        
        try {
            Log::debug("Creating new project");
            $model = $this->model->newInstance($attributes);
            $model->save();
            $this->resetModel();
            
            $projectNodeId = NavigationTree::projectId($model->id);
            $this->navigationTreeRepository->create($projectNodeId, $projectNodeId, $model->id, NavigationTree::PROJECT_TYPE, $model->name);
            
            DB::commit();
            event(new RepositoryEntityCreated($this, $model));
            Log::info(sprintf("Project %s created", $model->name));
            return $this->parserResult($model);
        } catch (Exception $e) {
            Log::error($e);
            DB::rollback();
            throw $e;
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \Prettus\Repository\Eloquent\BaseRepository::delete()
     */
    public function delete($id)
    {
        Log::debug(sprintf("Deleting project %d", $id));
        $this->applyScope();
    
        $_skipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
    
        $model = $this->find($id);
        $originalModel = clone $model;
    
        $this->skipPresenter($_skipPresenter);
        $this->resetModel();
    
        DB::beginTransaction();
        
        try {
            $deleted = $model->delete();
            
            if (!$deleted) {
                throw new Exception("Failed to delete entity: " . $model->id);
            }
            
            Log::debug("Deleting navigation tree node");
            $projectNodeId = NavigationTree::projectId($originalModel->id);
            $node = $this->navigationTreeRepository->find($projectNodeId, $projectNodeId);
            $deleted = $this->navigationTreeRepository->deleteWithAllChildren($node->ancestor, $node->descendant);
            
            if (!$deleted) {
                throw new Exception("Failed to delete node: " . $node->display_name);
            }

            DB::commit();
            event(new RepositoryEntityDeleted($this, $originalModel));
            Log::info(sprintf("Project %s deleted!", $originalModel->name));
            return $deleted;
        } catch (Exception $e) {
            Log::error($e);
            DB::rollback();
            throw $e;
        }
    }
    
    public function update(array $attributes, $id)
    {
        Log::debug(sprintf("Updating project %d", $id));
        $this->applyScope();
    
        $_skipPresenter = $this->skipPresenter;
    
        $this->skipPresenter(true);
        
        DB::beginTransaction();
        
        try {
            $model = $this->model->findOrFail($id);
            $model->fill($attributes);
            $model->save();
            
            $this->skipPresenter($_skipPresenter);
            $this->resetModel();
            
            Log::debug("Deleting navigation tree node");
            $projectNodeId = NavigationTree::projectId($model->id);
            $node = $this->navigationTreeRepository->update(
                $projectNodeId,
                $projectNodeId,
                $model->id,
                NavigationTree::PROJECT_TYPE,
                $model->name
            );
        
            DB::commit();
            event(new RepositoryEntityUpdated($this, $model));
            Log::info(sprintf("Project %s updated!", $model->name));
            return $this->parserResult($model);
        } catch (Exception $e) {
            Log::error($e);
            DB::rollback();
            throw $e;
        }
    }

    public function getExecutedTestCaseVersionIds($projectId)
    {
        return $this->model
            ->join('test_plans', 'projects.id', '=', 'test_plans.project_id')
            ->join('test_runs', 'test_plans.id', '=', 'test_runs.test_plan_id')
            ->join('executions', 'test_runs.id', '=', 'executions.test_run_id')
            ->join('test_cases_versions', 'executions.test_cases_versions_id', '=', 'test_cases_versions.id')
            ->where('projects.id', '=', $projectId)
            ->pluck('test_cases_versions.test_cases_id')
        ;
    }

    public function createSimpleProjectReport($projectId)
    {
        $numberOfTestPlans = $this->testPlansRepository->scopeQuery(function ($query) use ($projectId) {
            return $query->where('project_id', $projectId);
        })->all()->count();

        $numberOfTestSuites = $this->testSuitesRepository->scopeQuery(function ($query) use ($projectId) {
            return $query->where('project_id', $projectId);
        })->all()->count();

        $numberOfTestCases = $this->testCasesRepository->scopeQuery(function ($query) use ($projectId) {
            return $query
                ->join('test_suites', 'test_cases.test_suite_id', '=', 'test_suites.id')
                ->where('test_suites.project_id', $projectId)
            ;
        })->all()->count();

        $numberOfTestRuns = $this->testRunsRepository->scopeQuery(function ($query) use ($projectId) {
            return $query
                ->join('test_plans', 'test_runs.test_plan_id', '=', 'test_plans.id')
                ->where('test_plans.project_id', $projectId)
            ;
        })->all()->count();

        $executions = $this->executionsRepository->scopeQuery(function ($query) use ($projectId) {
            return $query
                ->join('test_runs', 'executions.test_run_id', '=', 'test_runs.id')
                ->join('test_plans', 'test_runs.test_plan_id', '=', 'test_plans.id')
                ->where('test_plans.project_id', $projectId)
            ;
        })->all();

        $numberOfExecutions = $executions->count();

        $executionsSummary = [];

        foreach ($executions as $execution) {
            $executionStatusId = $execution['execution_status_id'];
            if (!array_key_exists($executionStatusId, $executionsSummary)) {
                $executionsSummary[$executionStatusId] = 1;
            } else {
                $executionsSummary[$executionStatusId] = $executionsSummary[$executionStatusId] + 1;
            }
        }

        // for Not Run test cases
        $testCasesTimesExecutions = $numberOfTestCases * $numberOfTestRuns;
        $numberOfTestCasesNotRun  = $testCasesTimesExecutions - $numberOfExecutions;
        $executionsSummary[ExecutionStatuses::EXECUTION_STATUS_NOT_RUN] = $numberOfTestCasesNotRun;

        return [
            'test_plans_count' => $numberOfTestPlans,
            'test_suites_count' => $numberOfTestSuites,
            'test_cases_count' => $numberOfTestCases,
            'test_runs_count' => $numberOfTestRuns,
            'executions_count' => $numberOfExecutions,
            'executions_summary' => $executionsSummary
        ];
    }
}

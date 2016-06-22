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
use \Exception;
use Illuminate\Container\Container as Application;
use Log;
use Nestor\Entities\NavigationTree;
use Nestor\Entities\TestCases;
use Nestor\Entities\TestCasesVersions;
use Nestor\Repositories\TestCasesRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Events\RepositoryEntityCreated;
use Prettus\Repository\Events\RepositoryEntityUpdated;
use Prettus\Repository\Events\RepositoryEntityDeleted;

/**
 * Class TestCasesRepositoryEloquent
 * @package namespace Nestor\Repositories;
 */
class TestCasesRepositoryEloquent extends BaseRepository implements TestCasesRepository
{

    /**
     *
     * @var NavigationTreeRepository $navigationTreeRepository
     */
    protected $navigationTreeRepository;

    /**
     *
     * @param Application $app
     * @param NavigationTreeRepository $navigationTreeRepository
     */
    public function __construct(Application $app, NavigationTreeRepository $navigationTreeRepository)
    {
        parent::__construct($app);
        $this->navigationTreeRepository = $navigationTreeRepository;
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TestCases::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Prettus\Repository\Eloquent\BaseRepository::createWithAncestor()
     */
    public function createWithAncestor(array $testcaseAttributes, array $testcaseVersionAttributes, $ancestorNodeId)
    {
        // if (!is_null($this->testcaseValidator)) {
        //     $this->testcaseValidator->with($testcaseAttributes)->passesOrFail(ValidatorInterface::RULE_CREATE);
        // }

        // if (!is_null($this->testcaseVersionValidator)) {
        //     $this->testcaseVersionValidator->with($testcaseVersionAttributes)->passesOrFail(ValidatorInterface::RULE_CREATE);
        // }
        
        DB::beginTransaction();
        
        try {
            Log::debug("Creating test case");
            $testcase = $this->model->newInstance($testcaseAttributes);
            $testcase->save();
            $this->resetModel();

            $testcaseId = $testcase->id;

            $testcaseVersionAttributes['test_case_id'] = $testcaseId;
            $testcaseVersion = new TestCasesVersions(collect($testcaseVersionAttributes)->toArray());
            $testcaseVersion->save();

            $testCaseNodeId = NavigationTree::testCaseId($testcaseId);
            $this->navigationTreeRepository->create($ancestorNodeId, $testCaseNodeId, $testcaseId, NavigationTree::TEST_CASE_TYPE, $testcaseVersion->name, json_encode(array('execution_type_id' => $testcaseVersion->execution_type_id)));
            
            DB::commit();
            event(new RepositoryEntityCreated($this, $testcase));
            Log::info(sprintf("Test case %s created", $testcaseVersion->name));
            return $this->parserResult($testcase);
        } catch (Exception $e) {
            Log::error($e);
            DB::rollback();
            throw $e;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function findTestCaseWithVersion($id, $columns = array('*'))
    {
        $this->applyCriteria();
        $this->applyScope();

        // test case
        $testCase = $this
            ->model
            ->findOrFail($id, $columns);
        $this->resetModel();

        // version
        $version = $testCase->latestVersion();

        // labels
        //$labels = $version->labels()->get();

        // steps
        //$steps = $version->sortedSteps()->with(array('executionStatus'))->get();

        // execution type
        $executionType = $version->executionType()->firstOrFail();

        //$labels = $labels->toArray();
        //$testCase = $testCase->toArray();
        $version = $version->toArray();
        //$steps = $steps->toArray();
        $executionType = $executionType->toArray();

        //$version['labels'] = $labels;
        //$version['steps'] = $steps;
        $version['execution_type'] = $executionType;
        $testCase->version = $version;

        return $this->parserResult($testCase);
    }

    /**
     * {@inheritDoc}
     * @see \Prettus\Repository\Eloquent\BaseRepository::delete()
     */
    public function delete($id)
    {
        Log::debug(sprintf("Deleting test case %d", $id));
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
            $testCaseNodeId = NavigationTree::testCaseId($originalModel->id);
            $node = $this->navigationTreeRepository->find($testCaseNodeId, $testCaseNodeId);
            $deleted = $this->navigationTreeRepository->deleteWithAllChildren($node->ancestor, $node->descendant);
    
            if (!$deleted) {
                throw new Exception("Failed to delete node: " . $node->display_name);
            }
    
            DB::commit();
            event(new RepositoryEntityDeleted($this, $originalModel));
            Log::info(sprintf("Test Case %s deleted!", $originalModel->name));
            return $deleted;
        } catch (Exception $e) {
            Log::error($e);
            DB::rollback();
            throw $e;
        }
    }

    public function updateWithAncestor(array $testcaseVersionAttributes, $ancestorNodeId)
    {
        DB::beginTransaction();
        
        try {
            $testcase = $this->findTestCaseWithVersion($testcaseVersionAttributes['test_case_id']);
            Log::debug(sprintf("Updating test case %d", $testcaseVersionAttributes['test_case_id']));
            $version = $testcase['version'];
            $newVersion = intval($version['version']) + 1;
            Log::debug(sprintf("Creating a new version %d", $newVersion));
            $testcaseVersionAttributes['version'] = $newVersion;
            $testcaseVersion = new TestCasesVersions(collect($testcaseVersionAttributes)->toArray());
            $testcaseVersion->save();

            $testcase->version = $testcaseVersion->toArray();

            $testCaseNodeId = NavigationTree::testCaseId($testcaseVersionAttributes['test_case_id']);
            $this->navigationTreeRepository->update($testCaseNodeId, $testCaseNodeId, $testcaseVersionAttributes['test_case_id'], NavigationTree::TEST_CASE_TYPE, $testcaseVersion->name, json_encode(array('execution_type_id' => $testcaseVersion->execution_type_id)));
            
            DB::commit();
            event(new RepositoryEntityCreated($this, $testcase));
            Log::info(sprintf("Test case %s updated", $testcaseVersion->name));
            return $this->parserResult($testcase);
        } catch (Exception $e) {
            Log::error($e);
            DB::rollback();
            throw $e;
        }
    }
}

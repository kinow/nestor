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
use Nestor\Repositories\NavigationTreeRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
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
        
        try
        {
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
        } catch ( Exception $e )
        {
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
        
        try
        {
            $deleted = $model->delete();
            
            if (!$deleted)
            {
                throw new Exception("Failed to delete entity: " . $model->id);
            }
            
            Log::debug("Deleting navigation tree node");
            $projectNodeId = NavigationTree::projectId($originalModel->id);
            $node = $this->navigationTreeRepository->find($projectNodeId, $projectNodeId);
            $deleted = $this->navigationTreeRepository->deleteWithAllChildren($node->ancestor, $node->descendant);
            
            if (!$deleted)
            {
                throw new Exception("Failed to delete node: " . $node->display_name);
            }

            DB::commit();
            event(new RepositoryEntityDeleted($this, $originalModel));
            Log::info(sprintf("Project %s deleted!", $originalModel->name));
            return $deleted;
        } catch ( Exception $e )
        {
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
        
        try
        {
            $model = $this->model->findOrFail($id);
            $model->fill($attributes);
            $model->save();
            
            $this->skipPresenter($_skipPresenter);
            $this->resetModel();
            
            Log::debug("Deleting navigation tree node");
            $projectNodeId = NavigationTree::projectId($model->id);
            $node = $this->navigationTreeRepository->update(
				$projectNodeId, $projectNodeId, $model->id,
				NavigationTree::PROJECT_TYPE, $model->name
			);
        
            DB::commit();
            event(new RepositoryEntityUpdated($this, $model));
            Log::info(sprintf("Project %s updated!", $model->name));
            return $this->parserResult($model);
        } catch ( Exception $e )
        {
            Log::error($e);
            DB::rollback();
            throw $e;
        }
    }
    
}
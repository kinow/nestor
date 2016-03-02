<?php

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
        if (!is_null($this->validator))
        {
            $this->validator->with($attributes)->passesOrFail(ValidatorInterface::RULE_CREATE);
        }
        
        DB::beginTransaction();
        
        try
        {
            Log::debug("Creating new project");
            $model = $this->model->newInstance($attributes);
            $model->save();
            $this->resetModel();
            
            $this->navigationTreeRepository->create(NavigationTree::id(NavigationTree::PROJECT_TYPE, $model->id), NavigationTree::id(NavigationTree::PROJECT_TYPE, $model->id), $model->id, NavigationTree::PROJECT_TYPE, $model->name);
            
            event(new RepositoryEntityCreated($this, $model));
            
            DB::commit();
            Log::debug(sprintf("Project %s created", $model->name));
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
            
            event(new RepositoryEntityDeleted($this, $originalModel));
            
            Log::debug("Deleting navigation tree node");
            $projectNodeId = NavigationTree::projectId($originalModel->id);
            $node = $this->navigationTreeRepository->find($projectNodeId, $projectNodeId);
            $deleted = $this->navigationTreeRepository->deleteWithAllChildren($node->ancestor, $node->descendant);
            
            if (!$deleted)
            {
                throw new Exception("Failed to delete node: " . $node->display_name);
            }

            DB::commit();
            return $deleted;
        } catch ( Exception $e )
        {
            Log::error($e);
            DB::rollback();
            throw $e;
        }
    }
}
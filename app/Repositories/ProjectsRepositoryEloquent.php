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
            $model = $this->model->newInstance($attributes);
            $model->save();
            $this->resetModel();
            
            $this->navigationTreeRepository->create(NavigationTree::id(NavigationTree::PROJECT_TYPE, $model->id), NavigationTree::id(NavigationTree::PROJECT_TYPE, $model->id), $model->id, NavigationTree::PROJECT_TYPE, $model->name);
            
            event(new RepositoryEntityCreated($this, $model));
            
            DB::commit();
            return $this->parserResult($model);
        } catch ( Exception $e )
        {
            Log::error($e);
            DB::rollback();
            throw $e;
        }
    }
}
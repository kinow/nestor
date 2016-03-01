<?php

namespace Nestor\Repositories;

use DB;
use Exception;
use Nestor\Entities\Projects;
use Nestor\Repositories\NavigationTreeRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Nestor\Entities\NavigationTree;

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
     * @param NavigationTreeRepository $navigationTreeRepository            
     */
    public function __construct(NavigationTreeRepository $navigationTreeRepository)
    {
        $this->$navigationTreeRepository = $navigationTreeRepository;
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
            
            $node = array (
                    'ancestor' => NavigationTree::id(NavigationTree::PROJECT_TYPE, $model->id),
                    'descendant' => NavigationTree::id(NavigationTree::PROJECT_TYPE, $model->id),
                    'node_id' => $model->id,
                    'node_type_id' => NavigationTree::PROJECT_TYPE,
                    'display_name' => $model->name
            );
            if (!is_null($this->navigationTreeRepository->validator))
            {
                $this->navigationTreeRepository->validator->with($attributes)->passesOrFail(ValidatorInterface::RULE_CREATE);
            }
            
            event(new RepositoryEntityCreated($this, $model));
            
            return $this->parserResult($model);
        } catch ( Exception $e )
        {
            Log::error($e);
            DB::rollback();
            throw $e;
        }
    }
}
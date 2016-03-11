<?php

namespace Nestor\Repositories;

use DB;
use Exception;
use Illuminate\Container\Container as Application;
use Log;
use Nestor\Entities\NavigationTree;
use Nestor\Entities\TestSuites;
use Nestor\Repositories\TestSuitesRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Events\RepositoryEntityCreated;

/**
 * Class TestSuitesRepositoryEloquent
 *
 * @package namespace Nestor\Repositories;
 */
class TestSuitesRepositoryEloquent extends BaseRepository implements TestSuitesRepository
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
        return TestSuites::class;
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
    public function createWithAncestor(array $attributes, $ancestorNodeId)
    {
        if (!is_null($this->validator))
        {
            $this->validator->with($attributes)->passesOrFail(ValidatorInterface::RULE_CREATE);
        }
        
        DB::beginTransaction();
        
        try
        {
            Log::debug("Creating new test suite");
            $model = $this->model->newInstance($attributes);
            $model->save();
            $this->resetModel();
            
            $testSuiteNodeId = NavigationTree::testSuiteId($model->id);
            $this->navigationTreeRepository->create($ancestorNodeId, $testSuiteNodeId, $model->id, NavigationTree::TEST_SUITE_TYPE, $model->name);
            
            DB::commit();
            event(new RepositoryEntityCreated($this, $model));
            Log::info(sprintf("Test suite %s created", $model->name));
            return $this->parserResult($model);
        } catch ( Exception $e )
        {
            Log::error($e);
            DB::rollback();
            throw $e;
        }
    }
}

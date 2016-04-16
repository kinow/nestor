<?php

namespace Nestor\Repositories;

use DB;
use \Exception;
use Illuminate\Container\Container as Application;
use Log;
use Nestor\Entities\NavigationTree;
use Nestor\Entities\TestCases;
use Nestor\Entities\TestCasesVersions;
use Nestor\Repositories\TestCasesRepository;
use Nestor\Repositories\TestSuitesRepository;
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
        if (!is_null($this->testcaseValidator)) {
            $this->testcaseValidator->with($testcaseAttributes)->passesOrFail(ValidatorInterface::RULE_CREATE);
        }

        if (!is_null($this->testcaseVersionValidator)) {
            $this->testcaseVersionValidator->with($testcaseVersionAttributes)->passesOrFail(ValidatorInterface::RULE_CREATE);
        }
        
        DB::beginTransaction();
        
        try {
            Log::debug("Creating new test case");
            $testcase = $this->model->newInstance($testcaseAttributes);
            $testcase->save();
            $this->resetModel();

            $testcaseId = $testcase->id;

            $testcaseVersion = new TestCasesVersions(collect($testcaseVersionAttributes));
            $testcaseVersion->save();

            $testcase->version = $testcaseVersion;

            $testSuiteNodeId = NavigationTree::testSuiteId($testcaseId);
            $this->navigationTreeRepository->create($ancestorNodeId, $testSuiteNodeId, $testcaseId, NavigationTree::TEST_SUITE_TYPE, $testcaseVersion->name);
            
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
}

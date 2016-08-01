<?php

namespace Nestor\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Nestor\Repositories\TestRunsRepository;
use Nestor\Entities\TestRuns;
use Nestor\Validators\TestRunsValidator;

/**
 * Class TestRunsRepositoryEloquent
 * @package namespace Nestor\Repositories;
 */
class TestRunsRepositoryEloquent extends BaseRepository implements TestRunsRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TestRuns::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}

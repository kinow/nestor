<?php

namespace Nestor\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Nestor\Repositories\TestCasesRepository;
use Nestor\Entities\TestCases;

/**
 * Class TestCasesRepositoryEloquent
 * @package namespace Nestor\Repositories;
 */
class TestCasesRepositoryEloquent extends BaseRepository implements TestCasesRepository
{
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
}

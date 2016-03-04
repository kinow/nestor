<?php

namespace Nestor\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Nestor\Repositories\TestSuitesRepository;
use Nestor\Entities\TestSuites;

/**
 * Class TestSuitesRepositoryEloquent
 * @package namespace Nestor\Repositories;
 */
class TestSuitesRepositoryEloquent extends BaseRepository implements TestSuitesRepository
{
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
}

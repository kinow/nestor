<?php

namespace Nestor\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Nestor\Repositories\TestPlansRepository;
use Nestor\Entities\TestPlans;
use Nestor\Validators\TestPlansValidator;

/**
 * Class TestPlansRepositoryEloquent
 * @package namespace Nestor\Repositories;
 */
class TestPlansRepositoryEloquent extends BaseRepository implements TestPlansRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TestPlans::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}

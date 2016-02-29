<?php

namespace Nestor\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Nestor\Entities\ExecutionTypes;

/**
 * Class ExecutionTypesRepositoryEloquent
 * @package namespace Nestor\Repositories;
 */
class ExecutionTypesRepositoryEloquent extends BaseRepository implements ExecutionTypesRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ExecutionTypes::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria( app(RequestCriteria::class) );
    }
}
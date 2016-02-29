<?php

namespace Nestor\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Nestor\Entities\ExecutionStatuses;

/**
 * Class ExecutionStatusesRepositoryEloquent
 * @package namespace Nestor\Repositories;
 */
class ExecutionStatusesRepositoryEloquent extends BaseRepository implements ExecutionStatusesRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ExecutionStatuses::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria( app(RequestCriteria::class) );
    }
}
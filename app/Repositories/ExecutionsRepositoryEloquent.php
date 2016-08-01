<?php

namespace Nestor\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Nestor\Repositories\ExecutionsRepository;
use Nestor\Entities\Executions;
use Nestor\Validators\ExecutionsValidator;

/**
 * Class ExecutionsRepositoryEloquent
 * @package namespace Nestor\Repositories;
 */
class ExecutionsRepositoryEloquent extends BaseRepository implements ExecutionsRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Executions::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function execute($executionStatusesId, $notes, $testCaseId)
    {
        
    }
}

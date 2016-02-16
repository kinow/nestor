<?php

namespace Nestor\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Nestor\Entities\ProjectStatuses;

/**
 * Class ProjectStatusesRepositoryEloquent
 * @package namespace Nestor\Repositories;
 */
class ProjectStatusesRepositoryEloquent extends BaseRepository implements ProjectStatusesRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ProjectStatuses::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria( app(RequestCriteria::class) );
    }
}
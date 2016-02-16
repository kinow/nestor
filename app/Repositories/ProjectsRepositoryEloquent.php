<?php

namespace Nestor\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Nestor\Entities\Projects;

/**
 * Class ProjectsRepositoryEloquent
 * @package namespace Nestor\Repositories;
 */
class ProjectsRepositoryEloquent extends BaseRepository implements ProjectsRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Projects::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria( app(RequestCriteria::class) );
    }
}
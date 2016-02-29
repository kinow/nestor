<?php

namespace Nestor\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Nestor\Entities\NavigationTree;

/**
 * Class NavigationTreeRepositoryEloquent
 * @package namespace Nestor\Repositories;
 */
class NavigationTreeRepositoryEloquent extends BaseRepository implements NavigationTreeRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return NavigationTree::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria( app(RequestCriteria::class) );
    }
}
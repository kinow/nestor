<?php

namespace Nestor\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Nestor\Entities\NavigationTreeNodeTypes;

/**
 * Class NavigationTreeNodeTypesRepositoryEloquent
 * @package namespace Nestor\Repositories;
 */
class NavigationTreeNodeTypesRepositoryEloquent extends BaseRepository implements NavigationTreeNodeTypesRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return NavigationTreeNodeTypes::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria( app(RequestCriteria::class) );
    }
}
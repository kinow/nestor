<?php

namespace Nestor\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface TestSuitesRepository
 * @package namespace Nestor\Repositories;
 */
interface TestSuitesRepository extends RepositoryInterface
{
    
    /**
     * Save a new entity in repository
     *
     * @throws ValidatorException
     * @param array $attributes
     * @param string $parentNodeId
     * @return mixed
     */
    function createWithAncestor(array $attributes, $ancestorNodeId);
}

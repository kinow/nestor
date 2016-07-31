<?php

namespace Nestor\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface TestRunsRepository
 * @package namespace Nestor\Repositories;
 */
interface TestRunsRepository extends RepositoryInterface
{
    //
    public function create(array $payload);
}

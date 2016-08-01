<?php

namespace Nestor\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ExecutionsRepository
 * @package namespace Nestor\Repositories;
 */
interface ExecutionsRepository extends RepositoryInterface
{
    public function execute($executionStatusesId, $notes, $testCaseVersionId);
}

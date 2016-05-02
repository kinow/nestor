<?php

namespace Nestor\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface TestCasesRepository
 * @package namespace Nestor\Repositories;
 */
interface TestCasesRepository extends RepositoryInterface
{

    function createWithAncestor(array $testcaseAttributes, array $testcaseVersionAttributes, $ancestorNodeId);

    function findTestCaseWithVersion($id, $columns = array('*'));
}

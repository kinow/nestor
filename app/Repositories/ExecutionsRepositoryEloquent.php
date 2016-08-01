<?php

namespace Nestor\Repositories;

use DB;
use Log;

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

    public function execute($executionStatusesId, $notes, $testCaseVersionId)
    {
        Log::debug(sprintf('Executing test case %s', $testCaseId));
        DB::beginTransaction();
        try {
            Log::debug(sprintf('Creating a new execution for test case version %d with execution status %d', $testCaseVersionId, $executionStatusesId));
            
            $attributes = [
                'execution_statuses_id' => $executionStatusesId,
                'notes' => $notes,
                'test_case_versions_id' => $testCaseVersionId
            ];
            $model = $this->model->newInstance($attributes);

            Log::debug('Committing transaction');
            DB::commit();
            return $model;
        } catch (Exception $e) {
            Log::error($e);
            DB::rollback();
            throw $e;
        }
    }
}

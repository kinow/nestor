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

    public function execute($executionStatusesId, $notes, $testRunId, $testCaseVersionId)
    {
        Log::debug(sprintf('Executing test case version %s', $testCaseVersionId));
        DB::beginTransaction();
        try {
            Log::debug(sprintf('Creating a new execution for test case version %d with execution status %d', $testCaseVersionId, $executionStatusesId));
            
            $attributes = [
                'test_run_id' => $testRunId,
                'execution_status_id' => $executionStatusesId,
                'notes' => $notes,
                'test_case_version_id' => $testCaseVersionId
            ];
            $model = $this->model->newInstance($attributes);
            $model->save();

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

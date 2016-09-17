<?php
/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 Bruno P. Kinoshita, Peter Florijn
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

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
                'test_cases_versions_id' => $testCaseVersionId
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

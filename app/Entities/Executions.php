<?php

namespace Nestor\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Executions extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['test_run_id', 'test_case_version_id', 'execution_status_id'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'executions';

    public function testRun()
    {
        return $this->belongsTo('Nestor\\Entities\\TestRuns', 'test_run_id');
    }

    public function testCaseVersion()
    {
        return $this->belongsTo('Nestor\\Entities\\TestCasesVersions', 'test_case_version_id');
    }

    public function executionStatus()
    {
        return $this->belongsTo('Nestor\\Entities\\ExecutionStatuses', 'execution_status_id');
    }
}

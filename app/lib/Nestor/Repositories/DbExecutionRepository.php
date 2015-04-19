<?php namespace Nestor\Repositories;

use Nestor\Model\Execution;

class DbExecutionRepository extends DbBaseRepository implements ExecutionRepository {

	public function __construct(Execution $model)
	{
		parent::__construct($model);
	}

	public function findByTestRunId($test_run_id)
	{
		return $this->model->where('test_run_id', $test_run_id)->get()->toArray();
	}

	public function findByTestCaseVersionId($testCaseVersionId)
	{
		return $this->model->where('test_case_version_id', $testCaseVersionId)->get()->toArray();
	}

	public function findByExecutionStatusId($execution_status_id)
	{
		return $this->model->where('execution_status_id', $execution_status_id)->get()->toArray();
	}

	public function getExecutionsForTestCaseVersion($testRunId, $testCaseVersionId)
	{
		return $this
			->model
			->where('test_case_version_id', '=', $testCaseVersionId)
			->where('test_run_id', '=', $testRunId)
			->orderBy('executions.created_at', 'DESC')
			->with('executionStatus')
			->get()
			->toArray();
	}

}
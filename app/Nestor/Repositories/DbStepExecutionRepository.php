<?php namespace Nestor\Repositories;

use \StepExecution;

class DbStepExecutionRepository implements StepExecutionRepository {

	public function all()
	{
		return StepExecution::all();
	}

	public function find($id)
	{
		return StepExecution::find($id);
	}

	public function findByExecutionId($executionId)
	{
		return StepExecution::where('execution_id', $executionId)->get();
	}

	public function findByTestCaseStepId($test_case_step_id)
	{
		return StepExecution::where('test_case_step_id', $test_case_step_id)->get();
	}

	public function findByExecutionStatusId($execution_status_id)
	{
		return StepExecution::where('execution_status_id', $execution_status_id)->get();
	}

	public function create($execution_id, $test_case_step_id, $execution_status_id)
	{
		return StepExecution::create(compact('execution_id', 'test_case_step_id', 'execution_status_id'));
	}

	public function update($id, $execution_id, $test_case_step_id, $execution_status_id)
	{
		$stepExecution = $this->find($id);

		$stepExecution->fill(compact('execution_id', 'test_case_step_id', 'execution_status_id'))->save();

		return $stepExecution;
	}

	public function delete($id)
	{
		return StepExecution::where('id', $id)->delete();
	}

}
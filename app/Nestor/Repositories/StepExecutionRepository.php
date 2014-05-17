<?php namespace Nestor\Repositories;

interface StepExecutionRepository {

	public function all();

	public function find($id);

	public function findByExecutionId($execution_id);

	public function findByStepIdAndExecutionId($testCaseStepId, $executionId);

	public function findByTestCaseStepId($test_case_step_id);

	public function findByExecutionStatusId($execution_status_id);

	public function create($execution_id, $test_case_step_id, $execution_status_id);

	public function update($id, $execution_id, $test_case_step_id, $execution_status_id);

	public function delete($id);

}
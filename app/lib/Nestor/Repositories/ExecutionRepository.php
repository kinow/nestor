<?php namespace Nestor\Repositories;

interface ExecutionRepository {

	public function findByTestRunId($test_run_id);

	public function findByTestCaseVersionId($test_case_id);

	public function findByExecutionStatusId($execution_status_id);

	public function getExecutionsForTestCaseVersion($testCaseId, $testRunId);

}
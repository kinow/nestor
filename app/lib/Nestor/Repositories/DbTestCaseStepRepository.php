<?php
namespace Nestor\Repositories;

use Auth;
use Log;
use DB;

use Nestor\Model\TestCaseStep;
use Nestor\Model\TestCaseStepVersion;

class DbTestCaseStepRepository extends DbBaseRepository implements TestCaseStepRepository {

	public function __construct(TestCaseStep $model)
	{
		parent::__construct($model);
	}

	public function findByTestCaseVersion($testCaseVersionId) {
		return TestCaseStep::where('test_case_version_id', '=', $testCaseVersionId)->all();
	}

	public function createNewVersion(array $testCaseStepArray, array $testCaseStepVersionArray)
	{
		$pdo = DB::connection()->getPdo();
		$testCaseStep = TestCaseStep::create($testCaseStepArray)->toArray();
		$testCaseStepId = $pdo->lastInsertId();
		$testCaseStep['id'] = $testCaseStepId;
		$version = 1;
		Log::debug(sprintf('Creating initial test case step version for test case step %s', $testCaseStep['id']));
		$testCaseStepVersionArray['test_case_step_id'] = $testCaseStep['id'];
		$version = TestCaseStepVersion::create($testCaseStepVersionArray)->toArray();
		$versionId = $pdo->lastInsertId();
		$version['id'] = $versionId;
		Log::debug('Version 1 created');
		return array($testCaseStep, $version);
	}

}

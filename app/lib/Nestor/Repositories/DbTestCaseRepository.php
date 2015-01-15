<?php namespace Nestor\Repositories;

use Auth;
use Hash;
use Validator;
use DB;
use Log;

use Illuminate\Database\Query\Expression;

use Nestor\Model\TestCase2;
use Nestor\Model\TestCaseVersion;

class DbTestCaseRepository extends DbBaseRepository implements TestCaseRepository {

	public function __construct(TestCase2 $model)
	{
		parent::__construct($model);
	}

	public function getVersion($version)
	{
		return TestCaseVersion::where('version','=',$version)->firstOrFail();
	}

	public function isNameAvailable($id, $test_suite_id, $name)
	{
		return TestCase2::select('test_cases.*, test_case_versions.name')
			->join('test_case_versions', 'test_cases.id', '=', 'test_case_versions.id')
			->where('test_cases.id', '<>', $id)
			->where('test_suite_id', '=', $test_suite_id)
			->where(new Expression("lower(test_case_versions.name)"), '=', strtolower($name))
			->count() == 0;
	}

	public function addLabels($id, $labels) 
	{
		foreach($labels as $label) {
			TestCaseVersion::find($id)
				->labels()
				->attach($label['id']);
			Log::debug(sprintf('Label %s added %d', $label['name'], $id));
		}
	}

	public function createNewVersion(array $testCaseArray, array $testCaseVersionArray)
	{
		$pdo = DB::connection()->getPdo();
		$testCase = TestCase2::create($testCaseArray)->toArray();
		$testCaseId = $pdo->lastInsertId();
		$testCase['id'] = $testCaseId;
		Log::debug(sprintf('Creating initial test case version for test case %d', $testCase['id']));
		$testCaseVersionArray['test_case_id'] = $testCase['id'];
		$version = TestCaseVersion::create($testCaseVersionArray)->toArray();
		$versionId = $pdo->lastInsertId();
		$version['id'] = $versionId;
		return array($testCase, $version);
	}

	public function findTestCase($id)
	{
		$testCase = $this
			->model
			->firstOrFail();

		$version = $testCase->latestVersion();
		$testCase = $testCase->toArray();
		$version = $version->toArray();
		$testCase['version'] = $version;
		return $testCase;
	}

}

<?php namespace Nestor\Repositories;

use Auth, Hash, Validator;
use TestCase2;
use DB;
use Log;
use TestCaseVersion;

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
			->where(new \Illuminate\Database\Query\Expression("lower(test_case_versions.name)"), '=', strtolower($name))
			->count() == 0;
	}

}

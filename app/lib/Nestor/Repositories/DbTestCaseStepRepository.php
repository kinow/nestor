<?php
namespace Nestor\Repositories;

use Auth, Hash, Validator;
use TestCaseStep;
use TestCaseStepVersion;
use Log;
use DB;

class DbTestCaseStepRepository extends DbBaseRepository implements TestCaseStepRepository {

	public function findByTestCaseVersion($testCaseVersionId)
	{
		return TestCaseStep::where('test_case_version_id', '=', $testCaseVersionId)->all();
	}

}

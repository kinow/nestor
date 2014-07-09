<?php namespace Nestor\Util;

class JUnitProducer {

	/**
	 * It produces a JUnit XML using the array test suites. Each element of the array must be 
	 * a valid TestSuite object (not necessarily Eloquent). It will try to use ->testcases on 
	 * each test suite found to retrieve another array, with test cases.
	 * 
	 * @param array $testsuites
	 * @return \DomDocument
	 */
	public function produce(Array $testsuites)
	{
		$document = new \N98\JUnitXml\Document();

		foreach ($testsuites as $ts)
		{
			$suite = $document->addTestSuite();
			$timeStamp = new \DateTime();
			$suite->setName($ts->name);
			$suite->setTimestamp($timeStamp);
			$suite->setTime(0.344244);

			foreach ($ts->testcases as $tc) 
			{
				$testcase = $suite->addTestCase();
				$testcase->setName($tc->name);
				if ($tc->execution_status_id == 1)
					continue;
				if ($tc->execution_status_id == 3)
				{
					$testcase->addFailure($tc->notes, "Failed");
				}
				else if ($tc->execution_status_id == 4)
				{
					$testcase->addError($tc->notes, "Blocked");
				}
			}
		}

		return $document;
	}

}

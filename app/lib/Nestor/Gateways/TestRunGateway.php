<?php 
namespace Nestor\Gateways;

use Nestor\Repositories\TestRunRepository;

class TestRunGateway
{

	protected $testRunRepository;

	public function __construct(TestRunRepository $testRunRepository)
	{
		$this->testRunRepository = $testRunRepository;
	}

	public function findTestRun($testRunId)
	{
		return $this->testRunRepository->findWith($testRunId, array('testplan'));
	}

}
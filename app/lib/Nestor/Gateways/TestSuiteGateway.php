<?php
namespace Nestor\Gateways;

use Nestor\Repositories\TestSuiteRepository;

class TestSuiteGateway 
{

	protected $testSuiteRepository;

	public function __construct(TestSuiteRepository $testSuiteRepository)
	{
		$this->testSuiteRepository = $testSuiteRepository;
	}

	public function findByProject($projectId) 
	{
		return $this->testSuiteRepository->findByProject($projectId);
	}

}
<?php
namespace Nestor\Gateways;

use Nestor\Repositories\ProjectRepository;
use Nestor\Repositories\TestCaseRepository;
use Nestor\Repositories\ExecutionTypeRepository;
use Nestor\Repositories\NavigationTreeRepository;
use Nestor\Repositories\ExecutionStatusRepository;
use Nestor\Repositories\LabelRepository;
use Nestor\Repositories\TestSuiteRepository;

use Nestor\Model\Nodes;

class NodeGateway 
{

	protected $projectRepository;
	protected $testcaseRepository;
	protected $executionTypeRepository;
	protected $nodeRepository;
	protected $executionStatuses;
	protected $labelRepository;
	protected $testsuiteRepository;

	public function __construct(
		ProjectRepository $projectRepository, 
		TestCaseRepository $testcaseRepository, 
		ExecutionTypeRepository $executionTypeRepository, 
		NavigationTreeRepository $nodeRepository, 
		ExecutionStatusRepository $executionStatuses,
		LabelRepository $labelRepository,
		TestSuiteRepository $testsuiteRepository)
	{
		$this->projectRepository = $projectRepository;
		$this->testcaseRepository = $testcaseRepository;
		$this->executionTypeRepository = $executionTypeRepository;
		$this->nodeRepository = $nodeRepository;
		$this->executionStatuses = $executionStatuses;
		$this->labelRepository = $labelRepository;
		$this->testsuiteRepository = $testsuiteRepository;
	}

	public function findNodes($nodeId) 
	{
		$nodes = $this->nodeRepository->children($nodeId, 1 /* length*/);
		return $nodes;
	}

	public function moveNode($descendant, $ancestor)
	{
		$this->nodeRepository->move($descendant, $ancestor);
	}

}
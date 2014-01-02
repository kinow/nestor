<?php

use \Input;
use \HTML;
use Nestor\Repositories\ProjectRepository;
use Nestor\Repositories\TestCaseRepository;
use Nestor\Repositories\ExecutionTypeRepository;
use Nestor\Repositories\NavigationTreeRepository;

class SpecificationController extends \NavigationTreeController {

	/**
	 *
	 * @var Nestor\Repositories\ProjectRepository;
	 */
	protected $projects;

	/**
	 *
	 * @var Nestor\Repositories\TestCaseRepository;
	 */
	protected $testcases;

	/**
	 * The execution type repository implementation.
	 *
	 * @var Nestor\Repositories\ExecutionTypeRepository
	 */
	protected $executionTypes;

	/**
	 * @var Nestor\Repositories\NavigationTreeRepository
	 */
	protected $nodes;

	/**
	 * Current project in Session.
	 * @var Project
	 */
	protected $currentProject;

	/**
	 * Constructor.
	 *
	 * @param Nestor\Repositories\ProjectRepository         $projects
	 * @param Nestor\Repositories\ExecutionTypeRepository   $executionTypes
	 * @param Nestor\Repositories\NavigationTreeRepository  $nodes
	 * @return SpecificationController
	 */
	public function __construct(ProjectRepository $projects, TestCaseRepository $testcases, ExecutionTypeRepository $executionTypes, NavigationTreeRepository $nodes)
	{
		parent::__construct();
		$this->projects = $projects;
		$this->testcases = $testcases;
		$this->executionTypes = $executionTypes;
		$this->nodes = $nodes;
		$this->theme->setActive('specification');
	}

	/**
	 * Specification index controller.
	 *
	 * /specification/nodes
	 */
	public function getIndex()
	{
		$currentProject = $this->getCurrentProject();
		$nodes = $this->nodes->children('1-'.$currentProject->id, 1 /* length*/);
// 		$queries = DB::getQueryLog();
// 		$last_query = end($queries);
		$navigationTree = $this->createNavigationTree($nodes, '1-'.$currentProject->id);
		$navigationTreeHtml = $this->createTreeHTML($navigationTree, "", $this->theme->getThemeName());
		$args = array();
		$args['navigation_tree'] = $navigationTree;
		$args['navigation_tree_html'] = $navigationTreeHtml;
		$args['current_project'] = $this->currentProject;
		return $this->theme->scope('specification.index', $args)->render();
	}

	/**
	 * Specification nodes controller.
	 *
	 * /specification/nodes/(:any)
	 */
	public function getNodes($node_id)
	{
		$currentProject = $this->getCurrentProject();
		$nodes = $this->nodes->children('1-'.$currentProject->id, 1 /* length*/);
		$navigationTree = $this->createNavigationTree($nodes, '1-'.$currentProject->id);
		$navigationTreeHtml = $this->createTreeHTML($navigationTree, $node_id, $this->theme->getThemeName());
		$args = array();

		$node = $this->nodes->find($node_id, $node_id);
		$args['node'] = $node;

		// Create specific parameters depending on execution type
		if (isset($node) && $node->node_type_id == 2) // Test Suite?
		{
			$execution_types = $this->executionTypes->all();
			$args['execution_types'] = $execution_types;
			$execution_types_ids = array();
			foreach ($args['execution_types'] as $execution_type)
			{
				$execution_types_ids[$execution_type->id] = $execution_type->name;
			}
			$args['execution_type_ids'] = $execution_types_ids;
		}
		else if (isset($node) && $node->node_type_id == 3) // Test Case?
		{
			$execution_types = $this->executionTypes->all();
			$testcase = $this->testcases->find($node->node_id);
			if (isset($testcase) && !is_null($testcase))
			{
				foreach ($execution_types as $execution_type)
				{
					if ($execution_type->id == $testcase->execution_type_id)
					{
						$testcase->execution_type_name = $execution_type->name;
					}
				}
			}
			$args['testcase'] = $testcase;
		}
			$args['navigation_tree_html'] = $navigationTreeHtml;
			$args['navigation_tree'] = $navigationTree;
			$args['current_project'] = $this->currentProject;
			return $this->theme->scope('specification.index', $args)->render();
	}

}
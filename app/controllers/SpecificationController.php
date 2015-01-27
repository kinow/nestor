<?php

use Nestor\Model\Nodes;
use Nestor\Model\ExecutionStatus;
use Nestor\Util\NavigationTreeUtil;

class SpecificationController extends NavigationTreeController {

	protected $currentProject;

	public function __construct()
	{
		parent::__construct();
		$this->theme->setActive('specification');
	}

	/**
	 * Specification index controller.
	 *
	 * /specification/nodes
	 */
	public function getIndex()
	{
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Specification');
		// current project in the section to retrieve its children nodes
		$currentProject = $this->getCurrentProject();
		$nodeId = Nodes::id(Nodes::PROJECT_TYPE, $currentProject['id']);
		$nodes = HMVC::get("api/v1/nodes/$nodeId");

		// create a navigation tree
		$navigationTree = NavigationTreeUtil::createNavigationTree(
			$nodes, Nodes::id(Nodes::PROJECT_TYPE, $currentProject['id'])
		);

		// use it to create the HTML version
		$navigationTreeHtml = NavigationTreeUtil::createNavigationTreeHtml(
			$navigationTree, 
			NULL, 
			$this->theme->getThemeName()
		);

		$args = array();
		$args['nodes'] = $nodes;
		$args['navigation_tree'] = $navigationTree;
		$args['navigation_tree_html'] = $navigationTreeHtml;
		return $this->theme->scope('specification.index', $args)->render();
	}

	/**
	 * Specification nodes controller.
	 *
	 * /specification/nodes/(:any)
	 */
	public function getNodes($nodeId)
	{
		// current project in the section to retrieve its children nodes
		$currentProject = $this->getCurrentProject();
		$rootNodeId = Nodes::id(Nodes::PROJECT_TYPE, $currentProject['id']);
		$nodes = HMVC::get("api/v1/nodes/$rootNodeId");

		// create a navigation tree
		$navigationTree = NavigationTreeUtil::createNavigationTree(
			$nodes, Nodes::id(Nodes::PROJECT_TYPE, $currentProject['id'])
		);

		// use it to create the HTML version
		$navigationTreeHtml = NavigationTreeUtil::createNavigationTreeHtml(
			$navigationTree, 
			$nodeId, 
			$this->theme->getThemeName()
		);

		$node = HMVC::get("api/v1/nodes/$nodeId");
		$node = $node[0];

		$args = array();
		if (!NavigationTreeUtil::containsNode($navigationTree, $node)) {
			return Redirect::to('/specification/')
				->with('error', sprintf('The node %s does not belong to the current selected project', $nodeId));
		}
		$args['node'] = $node;
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Specification', URL::to('/specification/'))->
			add(sprintf('Node %s-%s', $node['node_type_id'], $node['node_id']));

		// Create specific parameters depending on execution type
		if (isset($node)) {
			// Project?
			if ($node['node_type_id'] == Nodes::PROJECT_TYPE) {
				$currentProjectId = $this->getCurrentProjectId();
				$testSuites = HMVC::get("api/v1/projects/$currentProjectId/testsuites/");
				$args['testsuites'] = $testSuites;
			// Test Suite?
			} else if ($node['node_type_id'] == Nodes::TEST_SUITE_TYPE) {
				$currentProjectId = $this->getCurrentProjectId();
				$testSuites = HMVC::get("api/v1/projects/$currentProjectId/testsuites/");
				$args['testsuites'] = $testSuites;

				$testSuite = HMVC::get(sprintf("api/v1/testsuites/%s", $node['node_id']));
				$args['testsuite'] = $testSuite;

				$labels = $testSuite['labels'];
				$args['labels'] = $labels;

				$executionTypes = HMVC::get("api/v1/executiontypes/");
				$args['execution_types'] = $executionTypes;

				$executionTypesIds = array();
				foreach ($executionTypes as $executionType)
				{
					$executionTypesIds[$executionType['id']] = $executionType['name'];
				}
				$args['execution_type_ids'] = $executionTypesIds;

				$executionStatuses = HMVC::get("api/v1/executionstatuses/");
				$args['execution_statuses'] = $executionStatuses;

				$executionStatusesIds = array();
				foreach ($executionStatuses as $executionStatus) 
				{
					if ($executionStatus['id'] == ExecutionStatus::NOT_RUN)
						continue; // Skip NOT RUN
					$executionStatusesIds[$executionStatus['id']] = $executionStatus['name'];
				}
				$args['execution_statuses_ids'] = $executionStatusesIds;
			// Test Case?	
			} else if ($node['node_type_id'] == Nodes::TEST_CASE_TYPE) {
				$testCase = HMVC::get(sprintf("api/v1/testcases/%s", $node['node_id']));
				$args['testcase'] = $testCase;
			}
		}
		$args['navigation_tree_html'] = $navigationTreeHtml;
		$args['navigation_tree'] = $navigationTree;
		$args['current_project'] = $this->currentProject;
		return $this->theme->scope('specification.index', $args)->render();
	}

}
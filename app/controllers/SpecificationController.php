<?php

use Nestor\Gateways\SpecificationGateway;
use Nestor\Model\Nodes;
use Nestor\Util\NavigationTreeUtil;

class SpecificationController extends NavigationTreeController {

	protected $specificationGateway;
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
		$nodeId = Nodes::id(Nodes::PROJECT_TYPE, $currentProject['id']);
		$nodes = HMVC::get("api/v1/nodes/$nodeId");

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
		if (!NavigationTreeUtil::containsNode($navigationTree, $node))
		{
			return Redirect::to('/specification/')
				->with('error', sprintf('The node %s does not belong to the current selected project', $nodeId));
		}
		$args['node'] = $node;
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Specification', URL::to('/specification/'))->
			add(sprintf('Node %s-%s', $node['node_type_id'], $node['node_id']));

		// Create specific parameters depending on execution type
		if (isset($node))
		{
			if ($node['node_type_id'] == 1) // Project?
			{
				//$testsuites = $currentProject->testsuites()->get();
				$currentProject = $this->getCurrentProject();
				$currentProjectId = $currentProject['id'];
				$testsuites = HMVC::get("api/v1/projects/$currentProjectId/testsuites/");
				$args['testsuites'] = $testsuites;
			}
			else if ($node['node_type_id'] == 2) // Test Suite?
			{
				$execution_types = $this->executionTypes->all();
				$testsuite = $this->testsuites->find($node->node_id);
				$labels = $testsuite->labels();
				$args['execution_types'] = $execution_types;
				$execution_types_ids = array();
				foreach ($args['execution_types'] as $execution_type)
				{
					$execution_types_ids[$execution_type->id] = $execution_type->name;
				}
				$args['execution_type_ids'] = $execution_types_ids;
				$execution_statuses = $this->executionStatuses->all();
				$args['execution_statuses'] = $execution_statuses;
				$execution_statuses_ids = array();
				foreach ($args['execution_statuses'] as $execution_status) 
				{
					if ($execution_status->id == 1 || $execution_status->id == 2)
						continue; // Skip NOT RUN
					$execution_statuses_ids[$execution_status->id] = $execution_status->name;
				}
				$args['testsuite'] = $testsuite;
				$args['execution_statuses_ids'] = $execution_statuses_ids;
				$args['labels'] = $labels;
				$testsuites = $currentProject->testsuites()->get();
				$args['testsuites'] = $testsuites;
			}
			else if ($node->node_type_id == 3) // Test Case?
			{
				$execution_types = $this->executionTypes->all();
				$testcase = $this->testcases->find($node->node_id);
				$labels = $testcase->latestVersion()->labels();
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
				$args['labels'] = $labels;
			}
		}
		$args['navigation_tree_html'] = $navigationTreeHtml;
		$args['navigation_tree'] = $navigationTree;
		$args['current_project'] = $this->currentProject;
		return $this->theme->scope('specification.index', $args)->render();
	}

	public function postMoveNode() 
	{
		$descendant = Input::get('descendant');
		$ancestor = Input::get('ancestor');
		Log::debug(sprintf('Moving %s under %s', $descendant, $ancestor));
		$this->nodes->move($descendant, $ancestor);
		return Response::json('OK');
	}

}
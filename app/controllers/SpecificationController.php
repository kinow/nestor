<?php

use \Input;
use \HTML;
use Nestor\Repositories\ProjectRepository;
use Nestor\Repositories\TestCaseRepository;
use Nestor\Repositories\ExecutionTypeRepository;
use Nestor\Repositories\NavigationTreeRepository;

class SpecificationController extends \BaseController {

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

		// Check if the current project has been set
		$this->beforeFilter('@isCurrentProjectSet');
	}

	/**
	 * Filter used to check if the current project is set in the session.
	 * Redirects to home page if not set.
	 */
	public function isCurrentProjectSet() {
		$currentProject = Session::get('current_project');
		if (isset($currentProject) && $currentProject)
		{
			$this->currentProject = unserialize($currentProject);
		}
		else
		{
			return Redirect::to('/')->with('flash', 'Choose a project first');
		}
	}

	/**
	 * Retrieves the current project set in Session.
	 */
	protected function getCurrentProject()
	{
		return unserialize(Session::get('current_project'));
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
		$navigationTreeHtml = $this->createTreeHTML($navigationTree, '1-'.$currentProject->id, $this->theme->getThemeName());
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

	// --------- Utility methods

	/**
	 * Create a navigation tree with the nodes returned from DB.
	 *
	 * @param array   $nodes
	 * @param NavigationTreeNode $root
	 * @return array
	 */
	protected function createNavigationTree($nodes, $root)
	{
		$tree = array();
		foreach ($nodes as $node)
		{
			$node = (object) $node;
			if ($node->ancestor == $node->descendant && $node->ancestor == $root)
			{
				$node->children = array();
				$tree[$root] = $node;
			}
			else if ($node->ancestor !== $node->descendant)
			{
				$this->addChild($tree, $node);
			}
		}
		$this->sortNavigationTree($tree);
		return $tree;
	}

	protected function sortNavigationTree(&$nodes)
	{
		// Sort by execution type and display name
		usort($nodes, function($left, $right) {
			$leftAncestor = $left->ancestor;
			$rightAncestor = $right->ancestor;
			list($leftExecutionType, $leftNodeId) = explode("-", $leftAncestor);
			list($rightExecutionType, $rightNodeId) = explode("-", $rightAncestor);
			if ($leftExecutionType > $rightExecutionType)
				return 1;
			elseif ($leftExecutionType < $rightExecutionType)
				return -1;
			else
				return $left->display_name > $right->display_name;
		});

		foreach ($nodes as $node)
		{
			$this->sortNavigationTree($node->children);
		}
	}

	/**
	 * Adds a child node into the navigation tree.
	 *
	 * @param array $tree
	 * @param NavigationTreeNode $node
	 */
	protected function addChild($tree, $node)
	{
		foreach ($tree as $edge)
		{
			if ($edge->descendant == $node->ancestor)
			{
				$node->children = array();
				$node->ancestor = $node->descendant;
				$edge->children[$node->descendant] = $node;
			}
			else
			{
				$this->addChild($edge->children, $node);
			}
		}
	}

	/**
	 * Creates the navigation tree HTML to be displayed in the theme UI.
	 *
	 * @param array $navigationTree
	 * @param int $nodeId selected node
	 * @param string $themeName Used to build HTML links with theme assets
	 * @return string HTML
	 */
	protected function createTreeHTML($navigation_tree = array(), $nodeId, $theme_name = '')
	{
		$buffer = '';
		if (is_null ( $navigation_tree ) || empty ( $navigation_tree ))
			return $buffer;

		foreach ($navigation_tree as $node) {
			$extra_classes = "";
			if ($node->descendant == $nodeId && $node->ancestor == $nodeId) {
				$extra_classes = " expanded active";
			}
			if ($node->node_type_id == 1) { // project
				$buffer .= "<ul id='treeData' style='display: none;'>";
				$buffer .= sprintf ( "<li data-icon='places/folder.png' class='expanded%s'>%s", $extra_classes, HTML::link ('/specification/nodes/' . $node->descendant, $node->display_name, array('target' => '_self')));
				if (! empty ( $node->children )) {
					$buffer .= "<ul>";
					$buffer .= $this->createTreeHTML ($node->children, $nodeId, $theme_name);
					$buffer .= "</ul>";
				}
				$buffer .= "</li></ul>";
			} else if ($node->node_type_id == 2) { // test suite
				$buffer .= sprintf ( "<li data-icon='actions/document-open.png' class='%s'>%s", $extra_classes, HTML::link ('/specification/nodes/' . $node->descendant, $node->display_name, array('target' => '_self')));
				if (! empty ( $node->children )) {
					$buffer .= "<ul>";
					$buffer .= $this->createTreeHTML ($node->children, $nodeId, $theme_name);
					$buffer .= "</ul>";
				}
				$buffer .= "</li>";
			} else {
				$buffer .= sprintf ( "<li data-icon='mimetypes/text-x-generic.png' class='%s'>%s</li>", $extra_classes, HTML::link ('/specification/nodes/' . $node->descendant, $node->display_name, array('target' => '_self')));
			}
		}

		return $buffer;
	}

}
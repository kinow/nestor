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

	protected $currentProject;

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

	public function isCurrentProjectSet() {
		$current_project = Session::get('current_project');
		if (isset($current_project) && $current_project)
		{
			$this->currentProject = unserialize($current_project);
		}
		else
		{
			return Redirect::to('/')
				->with('flash', 'Choose a project first');
		}
	}

	public function getIndex()
	{
		$navigation_tree_nodes = $this->nodes->all();
		$navigation_tree = $this->create_navigation_tree($navigation_tree_nodes->toArray(), 0, $this->currentProject);
		$navigation_tree_html = $this->create_navigation_tree_html($navigation_tree, 0, $this->currentProject, $this->theme->getThemeName());
		$args = array();
		$args['navigation_tree'] = $navigation_tree;
		$args['navigation_tree_html'] = $navigation_tree_html;
		$args['current_project'] = $this->currentProject;
		return $this->theme->scope('specification.index', $args)->render();
	}

	public function getNodes($node_id)
	{
		$navigation_tree_nodes = $this->nodes->all();
		$navigation_tree = $this->create_navigation_tree($navigation_tree_nodes->toArray(), 0, $this->currentProject);
		$navigation_tree_html = $this->create_navigation_tree_html($navigation_tree, $node_id, $this->currentProject, $this->theme->getThemeName());

		$args = array();

		$node = $this->nodes->find($node_id);
		$args['node'] = $node;

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

		$args['navigation_tree'] = $navigation_tree;
		$args['navigation_tree_html'] = $navigation_tree_html;
		$args['current_project'] = $this->currentProject;
		return $this->theme->scope('specification.index', $args)->render();
	}

	private function create_navigation_tree(array $navigation_tree_nodes, $parent_id = 0, $active_project) {
		$tree = array();

		foreach ($navigation_tree_nodes as $node) {
			$node = (object) $node;
			if ($node->node_type_id == 1 && $node->node_id != $active_project->id)
			{
				continue; // we want only the current project to be displayed in the navigation tree
			}
			if ($node->parent_id == $parent_id || ($node->parent_id == null && $parent_id == 0)) {
				$children = $this->create_navigation_tree($navigation_tree_nodes, $node->id, $active_project);
				if ($children) {
					$node->children = $children;
				} else {
					$node->children = array();
				}
				$tree[$node->id] = $node;
			}
		}

		return $tree;
	}

	private function create_navigation_tree_html($navigation_tree = array(), $node_id, $last_parent = 0, $theme_name = '')
	{
		$buffer = '';
		if (is_null ( $navigation_tree ) || empty ( $navigation_tree ))
			return $buffer;

		foreach ( $navigation_tree as $node ) {
			$extra_classes = "";
			if ($node->id == $node_id) {
				$extra_classes = " expanded active";
			}
			if ($node->node_type_id == 1) { // project
				$buffer .= "<ul id='treeData' style='display: none;'>";
				$buffer .= sprintf ( "<li data-icon='places/folder.png' class='expanded%s'>%s", $extra_classes, HTML::link ('/specification/nodes/' . $node->id, $node->display_name, array('target' => '_self')));
				if (! empty ( $node->children )) {
					$buffer .= "<ul>";
					$buffer .= $this->create_navigation_tree_html ( $node->children, $node_id, $node->id, $theme_name);
					$buffer .= "</ul>";
				}
				$buffer .= "</li></ul>";
			} else if ($node->node_type_id == 2) { // test suite
			                                       // if ($node->parent_id != $last_parent)
			                                       // echo "<ul>";
				$buffer .= sprintf ( "<li data-icon='actions/document-open.png' class='%s'>%s", $extra_classes, HTML::link ('/specification/nodes/' . $node->id, $node->display_name, array('target' => '_self')));
				if (! empty ( $node->children )) {
					$buffer .= "<ul>";
					$buffer .= $this->create_navigation_tree_html ($node->children, $node_id, $node->parent_id, $theme_name);
					$buffer .= "</ul>";
				}
				// if ($node->parent_id != $last_parent)
				// echo "</ul>";
				$buffer .= "</li>";
			} else {
				$buffer .= sprintf ( "<li data-icon='mimetypes/text-x-generic.png' class='%s'>%s</li>", $extra_classes, HTML::link ('/specification/nodes/' . $node->id, $node->display_name, array('target' => '_self')));
			}
		}

		return $buffer;
	}
}
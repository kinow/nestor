<?php

use \Input;
use \HTML;
use Nestor\Repositories\ProjectRepository;
use Nestor\Repositories\TestCaseRepository;
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
	 * @var Nestor\Repositories\NavigationTreeRepository
	 */
	protected $nodes;

	public function __construct(ProjectRepository $projects, TestCaseRepository $testcases, NavigationTreeRepository $nodes)
	{
		parent::__construct();
		$this->projects = $projects;
		$this->testcases = $testcases;
		$this->nodes = $nodes;
		$this->theme->setActive('specification');
	}

	public function getIndex()
	{
		$current_project = unserialize(Session::get('current_project'));
		$navigation_tree_nodes = $this->nodes->all();
		$navigation_tree = $this->create_navigation_tree($navigation_tree_nodes->toArray(), 0, $current_project);
		$navigation_tree_html = $this->create_navigation_tree_html($navigation_tree, 0, $current_project, $this->theme->getThemeName());
		$args = array();
		$args['navigation_tree'] = $navigation_tree;
		$args['navigation_tree_html'] = $navigation_tree_html;
		$args['current_project'] = $current_project;
		return $this->theme->scope('specification.index', $args)->render();
	}

	public function getNodes($node_id)
	{
		$current_project = unserialize(Session::get('current_project'));
		$navigation_tree_nodes = $this->nodes->all();
		$navigation_tree = $this->create_navigation_tree($navigation_tree_nodes->toArray(), 0, $current_project);
		$navigation_tree_html = $this->create_navigation_tree_html($navigation_tree, $node_id, $current_project, $this->theme->getThemeName());

		$args = array();

		$node = $this->nodes->find($node_id);
		$args['node'] = $node;
		// Test Case?
		if (isset($node) && $node->node_type_id == 3) {
			$testcase = $this->testcases->find($node->node_id);
			$args['testcase'] = $testcase;
		}

		$args['navigation_tree'] = $navigation_tree;
		$args['navigation_tree_html'] = $navigation_tree_html;
		$args['current_project'] = $current_project;
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
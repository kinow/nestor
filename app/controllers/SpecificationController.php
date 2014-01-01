<?php

use \Input;
use \HTML;
use Log;
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

	protected function getCurrentProject()
	{
		return unserialize(Session::get('current_project'));
	}

	public function getIndex()
	{
		$current_project = $this->getCurrentProject();
		$navigation_tree_nodes = $this->nodes->children('1-'.$current_project->id, 2 /* length*/);
		$navigation_tree = $this->create_navigation_tree($navigation_tree_nodes->toArray(), '1-'.$current_project->id);
		$navigation_tree_html = $this->create_tree_html($navigation_tree, '1-'.$current_project->id, $this->theme->getThemeName());
		$args = array();
		$args['navigation_tree_html'] = $navigation_tree_html;
		$args['current_project'] = $this->currentProject;
		return $this->theme->scope('specification.index', $args)->render();
	}

	public function getNodes($node_id)
	{
		$current_project = $this->getCurrentProject();
		$navigation_tree_nodes = $this->nodes->children('1-'.$current_project->id, 1 /* length*/);
		$child_tree = $this->nodes->parents($node_id);
//  		$queries = DB::getQueryLog();
//  		$last_query = end($queries);
		$navigation_tree = $this->create_merged_navigation_tree($navigation_tree_nodes->toArray(), $child_tree, $node_id);
		$navigation_tree_html = $this->create_tree_html2($navigation_tree, $node_id, $this->currentProject, $this->theme->getThemeName());
		$args = array();

		$node = $this->nodes->find($node_id, $node_id);
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
// 		foreach($navigation_tree_nodes as $n)
// 		{
// 			print ($n->ancestor . '/' . $n->descendant);
// 			print "<br/>";
// 		}
		$args['navigation_tree_html'] = $navigation_tree_html;
		$args['navigation_tree'] = $navigation_tree;
		$args['current_project'] = $this->currentProject;
		return $this->theme->scope('specification.index', $args)->render();
	}

	private function create_navigation_tree(array $nodes, $node_id = -1)
	{
		$tree = array();
		foreach ($nodes as $node)
		{
			$node = (object) $node;
			if (isset($tree[$node->ancestor]))
			{
				$ancestor = $tree[$node->ancestor];
				$ancestor->children[$node->descendant] = $node;
			}
			else
			{
				$node->children = array();
				$tree[$node->ancestor] = $node;
			}
		}
		return $tree;
	}

	private function create_merged_navigation_tree(array $nodes, $parents, $node_id = -1)
	{
		$tree = array();
		foreach ($nodes as $node)
		{
			$node = (object) $node;
			if (isset($tree[$node->ancestor]))
			{
				if ($node->length > 1)
					continue;
				$ancestor = $tree[$node->ancestor];
				$ancestor->children[$node->descendant] = $node;
			}
			else
			{
				$node->children = array();
				$tree[$node->ancestor] = $node;
			}
		}

		foreach ($parents as $node)
		{
			$node = (object) $node;
			if (isset($tree[$node->ancestor]))
			{
				if ($node->length > 0)
					continue;
				if ($node->ancestor == $node->descendant)
					continue;
				$ancestor = $tree[$node->ancestor];
				if (!isset($ancestor->children[$node->descendant]))
					$ancestor->children[$node->descendant] = $node;
			}
			else
			{
				if ($node->ancestor == $node->descendant)
					continue;
				$node->children = array();
				$this->addChild($tree, $node);
			}
		}

		//echo var_dump($tree);exit;

		return $tree;
	}

	private function addChild($tree, $node)
	{
		if (isset($tree[$node->ancestor]))
		{
			$temp = $tree[$node->ancestor];
			if (!isset($temp->children))
				$temp->children = array();
			$temp->children[$node->descendant] = $node;
		}
		else
		{
			foreach ($tree as $t)
			{
				if (isset($t->children))
				{
					$this->addChild($t->children, $node);
				}
			}
		}
	}

	private function create_tree_html($navigation_tree = array(), $project_id, $theme_name = '')
	{
		$buffer = '';
		if (is_null ( $navigation_tree ) || empty ( $navigation_tree ))
			return $buffer;

		foreach ( $navigation_tree as $node ) {
			$extra_classes = "";
			if ($node->descendant == $project_id) {
				$extra_classes = " expanded active";
			}
			if ($node->node_type_id == 1) { // project
				$buffer .= "<ul id='treeData' style='display: none;'>";
				$buffer .= sprintf ( "<li data-icon='places/folder.png' class='expanded%s'>%s", $extra_classes, HTML::link ('/specification/nodes/' . $node->descendant, $node->display_name, array('target' => '_self')));
				if (! empty ( $node->children )) {
					$buffer .= "<ul>";
					$buffer .= $this->create_tree_html ($node->children, $project_id, $theme_name);
					$buffer .= "</ul>";
				}
				$buffer .= "</li></ul>";
			} else if ($node->node_type_id == 2) { // test suite
				$buffer .= sprintf ( "<li data-icon='actions/document-open.png' class='%s'>%s", $extra_classes, HTML::link ('/specification/nodes/' . $node->descendant, $node->display_name, array('target' => '_self')));
				if (! empty ( $node->children )) {
					$buffer .= "<ul>";
					$buffer .= $this->create_tree_html ($node->children, $node_id, $theme_name);
					$buffer .= "</ul>";
				}
				$buffer .= "</li>";
			} else {
			$buffer .= sprintf ( "<li data-icon='mimetypes/text-x-generic.png' class='%s'>%s</li>", $extra_classes, HTML::link ('/specification/nodes/' . $node->id, $node->display_name, array('target' => '_self')));
			}
		}

		return $buffer;
	}

	private function create_tree_html2($navigation_tree = array(), $node_id, $last_parent = 0, $theme_name = '')
	{
		$buffer = '';
		if (is_null ( $navigation_tree ) || empty ( $navigation_tree ))
			return $buffer;

		foreach ( $navigation_tree as $node ) {
			$extra_classes = "";
			if ($node->descendant == $node_id) {
				$extra_classes = " expanded active";
			}
			if ($node->node_type_id == 1) { // project
				$buffer .= "<ul id='treeData' style='display: none;'>";
				$buffer .= sprintf ( "<li data-icon='places/folder.png' class='expanded%s'>%s", $extra_classes, HTML::link ('/specification/nodes/' . $node->descendant, $node->display_name, array('target' => '_self')));
				if (! empty ( $node->children )) {
					$buffer .= "<ul>";
					$buffer .= $this->create_tree_html2 ($node->children, $node_id, $node->descendant, $theme_name);
					$buffer .= "</ul>";
				}
				$buffer .= "</li></ul>";
			} else if ($node->node_type_id == 2) { // test suite
				$buffer .= sprintf ( "<li data-icon='actions/document-open.png' class='%s'>%s", $extra_classes, HTML::link ('/specification/nodes/' . $node->descendant, $node->display_name, array('target' => '_self')));
				if (! empty ( $node->children )) {
					$buffer .= "<ul>";
					$buffer .= $this->create_tree_html2 ($node->children, $node_id, $node->descendant, $theme_name);
					$buffer .= "</ul>";
				}
				// if ($node->parent_id != $last_parent)
				// echo "</ul>";
				$buffer .= "</li>";
			} else {
				$buffer .= sprintf ( "<li data-icon='mimetypes/text-x-generic.png' class='%s'>%s</li>", $extra_classes, HTML::link ('/specification/nodes/' . $node->descendant, $node->display_name, array('target' => '_self')));
			}
		}

		return $buffer;
	}
}
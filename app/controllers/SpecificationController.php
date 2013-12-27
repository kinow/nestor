<?php

use \Input;
use \HTML;
use Nestor\Repositories\ProjectRepository;
use Nestor\Repositories\NavigationTreeRepository;

class SpecificationController extends \BaseController {

	/**
	 *
	 * @var Nestor\Repositories\ProjectRepository;
	 */
	protected $projects;

	/**
	 * @var Nestor\Repositories\NavigationTreeRepository
	 */
	protected $nodes;

	public function __construct(ProjectRepository $projects, NavigationTreeRepository $nodes)
	{
		parent::__construct();
		$this->projects = $projects;
		$this->nodes = $nodes;
		$this->theme->setActive('specification');
	}

	public function getIndex()
	{
		$current_project = unserialize(Session::get('current_project'));
		$node_id = Input::get('');
		$navigation_tree_nodes = $this->nodes->all();
		$navigation_tree = $this->get_navigation_tree($navigation_tree_nodes, 0, $current_project);
		// Test Case?
		if (isset($node) && $node->node_type_id == 3) {
			$testcase = $this->testcases_dao->get($node->node_id);
			$this->twiggy->set('testcase', $testcase);
		}
		$args = array();
		$args['navigation_tree'] = $navigation_tree;
		return $this->theme->scope('specification.index')->render();
	}

	private function get_navigation_tree($navigation_tree = array(), $node_id, $last_parent = 0, $buffer = "")
	{
		if (is_null ( $navigation_tree ) || empty ( $navigation_tree ))
			return $buffer;

		foreach ( $navigation_tree as $node ) {
			$extra_classes = "";
			if ($node->id == $node_id) {
				$extra_classes = " expanded active";
			}
			if ($node->node_type_id == 1) { // project
				$buffer .= "<ul id='treeData' style='display: none;'>";
				$buffer .= sprintf ( "<li data-icon='places/folder.png' class='expanded%s'><a target='_self' href='%s'>%s</a>", $extra_classes, HTML::link ( '/specification/nodes/' . $node->id ), $node->display_name );
				if (! empty ( $node->children )) {
					$buffer .= "<ul>";
					print_navigation_tree ( $node->children, $node_id, $node->id, $buffer );
					$buffer .= "</ul>";
				}
				$buffer .= "</li></ul>";
			} else if ($node->node_type_id == 2) { // test suite
			                                       // if ($node->parent_id != $last_parent)
			                                       // echo "<ul>";
				$buffer .= sprintf ( "<li data-icon='actions/document-open.png' class='%s'><a target='_self' href='%s'>%s</a>", $extra_classes, HTML::link ( '/specification/nodes/' . $node->id ), $node->display_name );
				if (! empty ( $node->children )) {
					$buffer .= "<ul>";
					print_navigation_tree ( $node->children, $node_id, $node->parent_id, $buffer );
					$buffer .= "</ul>";
				}
				// if ($node->parent_id != $last_parent)
				// echo "</ul>";
				$buffer .= "</li>";
			} else {
				$buffer .= sprintf ( "<li data-icon='mimetypes/text-x-generic.png' class='%s'><a target='_self' href='%s'>%s</a></li>", $extra_classes, HTML::link ( '/specification/nodes/' . $node->id ), $node->display_name );
			}
		}

		return $buffer;
	}
}
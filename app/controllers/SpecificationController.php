<?php

use \Input;
use Nestor\Repositories\ProjectRepository;

class SpecificationController extends \BaseController {

	/**
	 *
	 * @var Nestor\Repositories\ProjectRepository;
	 */
	protected $projects;

	public function __construct(ProjectRepository $projects)
	{
		$this->projects = $projects;
	}

	public function getIndex()
	{
		$node_id = Input::get('');
		$navigation_tree_nodes = $this->navigation_tree_dao->all();
		$navigation_tree = $this->_create_navigation_tree($navigation_tree_nodes, 0, $active_project);
		foreach ($navigation_tree as $id => $navigation_tree_node) {
			if ($id != $active_project->id) {
				unset($navigation_tree[$id]);
			}
		}
		// Test Case?
		if (isset($node) && $node->node_type_id == 3) {
			$testcase = $this->testcases_dao->get($node->node_id);
			$this->twiggy->set('testcase', $testcase);
		}
		$tree = $this->print_navigation_tree();
		return $this->theme->scope ( 'specification.index' )->render ();
	}

	private function print_navigation_tree($navigation_tree = array(), $node_id, $last_parent = 0, $buffer = "")
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
				$buffer .= sprintf ( "<li data-icon='places/folder.png' class='expanded%s'><a target='_self' href='%s'>%s</a>", $extra_classes, site_url ( '/specification/nodes/' . $node->id ), $node->display_name );
				if (! empty ( $node->children )) {
					$buffer .= "<ul>";
					print_navigation_tree ( $node->children, $node_id, $node->id, $buffer );
					$buffer .= "</ul>";
				}
				$buffer .= "</li></ul>";
			} else if ($node->node_type_id == 2) { // test suite
			                                       // if ($node->parent_id != $last_parent)
			                                       // echo "<ul>";
				$buffer .= sprintf ( "<li data-icon='actions/document-open.png' class='%s'><a target='_self' href='%s'>%s</a>", $extra_classes, site_url ( '/specification/nodes/' . $node->id ), $node->display_name );
				if (! empty ( $node->children )) {
					$buffer .= "<ul>";
					print_navigation_tree ( $node->children, $node_id, $node->parent_id, $buffer );
					$buffer .= "</ul>";
				}
				// if ($node->parent_id != $last_parent)
				// echo "</ul>";
				$buffer .= "</li>";
			} else {
				$buffer .= sprintf ( "<li data-icon='mimetypes/text-x-generic.png' class='%s'><a target='_self' href='%s'>%s</a></li>", $extra_classes, site_url ( '/specification/nodes/' . $node->id ), $node->display_name );
			}
		}

		return $buffer;
	}
}
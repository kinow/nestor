<?php

use Theme;
use Input;
use Nestor\Repositories\ProjectRepository;

class SpecificationController extends \BaseController {

	public function getIndex()
	{
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
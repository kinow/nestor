<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * NestorQA
 * 
 * Test management system.
 * 
 * @package NestorQA
 * @author NestorQA team
 * @copyright Copyright (c) 2012 - 2013, NestorQA team
 * @license MIT
 * @link http://nestor-qa.org
 * @since 0.0.2
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * NestorQA Helpers
 *
 * @package		NestorQA
 * @subpackage	Helpers
 * @category	Helpers
 * @author NestorQA team
 * @link http://nestor-qa.org
 */

// ------------------------------------------------------------------------

/**
 * Version
 * 
 * Displays product version
 * 
 * @access public
 * @return string
 */
if ( ! function_exists('nestor_version')) {
	function nestor_version() {
		$ci =& get_instance();
		$ci->config->load('nestor');
		$version = $ci->config->item('nestor_version');
		if ($version) 
			return $version;
		return 'N/A';
	}
}

/**
 * Prints navigation tree
 * 
 * @accees public
 * @return void
 */
if ( ! function_exists('print_navigation_tree')) {
	function print_navigation_tree($navigation_tree = array(), $node_id, $last_parent = 0) {
		if (is_null($navigation_tree) || empty($navigation_tree)) 
			return;
		
		foreach ($navigation_tree as $node) {
			$extra_classes = "";
			if ($node->id == $node_id) {
				$extra_classes = " expanded active";
			}
			if ($node->node_type_id == 1) { // project
				echo "<ul id='treeData' style='display: none;'>";
				echo sprintf("<li data-icon='places/folder.png' class='expanded%s'><a target='_self' href='%s'>%s</a>", $extra_classes, site_url('/specification?node_id='.$node->id), $node->display_name);
				if (!empty($node->children)) {
					echo "<ul>";
					print_navigation_tree($node->children, $node_id, $node->id);
					echo "</ul>";
				}
				echo "</li></ul>";
			} else if ($node->node_type_id == 2) { // test suite
// 				if ($node->parent_id != $last_parent)
// 					echo "<ul>";
				echo sprintf("<li data-icon='actions/document-open.png' class='%s'><a target='_self' href='%s'>%s</a>", $extra_classes, site_url('/specification?node_id='.$node->id), $node->display_name);
				if (!empty($node->children)) {
					echo "<ul>";
					print_navigation_tree($node->children, $node_id, $node->parent_id);
					echo "</ul>";
				}
// 				if ($node->parent_id != $last_parent)
// 					echo "</ul>";
				echo "</li>";
			} else {
				echo sprintf("<li data-icon='mimetypes/text-x-generic.png' class='%s'><a target='_self' href='%s'>%s</a></li>", $extra_classes, site_url('/specification?node_id='.$node->id), $node->display_name);
			}
		}
	}
}

/* End of file nestor_helper.php */
/* Location: ./application/helpers/nestor_helper.php */

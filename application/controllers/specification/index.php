<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends Twiggy_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('projects');
		$this->load->model('navigation_tree_dao');
		$this->load->model('testcases_dao');
	}
	
	public function index() {
		// Projects
		$active_project = $this->get_current_project();
		$projects = $this->projects->all();
		// Node ID
		$node_id = $this->input->get('node_id');
		$node = NULL;
		if (isset($node_id) && $node_id > 0)
			$node = $this->navigation_tree_dao->get_by_node_id($node_id);
		//$navigation_tree_nodes = $this->navigation_tree_dao->get_by_node_id($active_project->id);
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
		// UI
		$this->twiggy->set('active_project', $active_project);
		$this->twiggy->set('projects', $projects);
		$this->twiggy->set('active', 'specification');
		$this->twiggy->set('node_id', $node_id);
		$this->twiggy->set('node', $node);
		$this->twiggy->set('navigation_tree_nodes', $navigation_tree_nodes);
		$this->twiggy->set('navigation_tree', $navigation_tree);
		$this->twiggy->display('specification/index');
	}
	
	private function _create_navigation_tree(array &$navigation_tree_nodes, $parent_id = 0, $active_project) {
		$tree = array();
		
		foreach ($navigation_tree_nodes as $node) {
			if ($node->parent_id == $parent_id) {
				$children = $this->_create_navigation_tree($navigation_tree_nodes, $node->id, $active_project);
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
}

/* End of file index.php */
/* Location: ./application/controllers/specification/index.php */
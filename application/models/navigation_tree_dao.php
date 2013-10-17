<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Navigation_Tree_Dao extends CI_Model {
	
	/**
	 * Creates a new node into the navigation tree.
	 * 
	 * @visibility public
	 * @param object $navigation_tree_node
	 */
	public function create($navigation_tree_node) {
		if (!$navigation_tree_node || empty($navigation_tree_node) || !is_object($navigation_tree_node)) 
			return;
		
		if (!$this->db->insert('navigation_tree', $navigation_tree_node)) {
			throw new Exception('Failed to create navigation tree node');
		}
	}
	
	public function all($left_limit = 0, $right_limit = 0) {
		if ($left_limit > 0)
			$this->db->limit($left_limit, $right_limit);
		return $this->db->get('navigation_tree')->result();
	}
	
	public function get_by_project_id($project_id) {
		$this->db->where('parent_id', 0);
		$this->db->where('node_type_id', 1); // FIXME: constants
		$this->db->where('node_id', $project_id);
		return $this->db->get('navigation_tree')->result();
	}
	
	public function get_by_node_id($node_id, $left_limit = 0, $right_limit = 0) {
		if ($left_limit > 0)
			$this->db->limit($left_limit, $right_limit);
		$this->db->where('id', $node_id);
		return $this->db->get('navigation_tree')->row();
	}
	
}
<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Projects extends CI_Model {

	public function all($left_limit = 0, $right_limit = 0) {
		if ($left_limit > 0)
			$this->db->limit($left_limit, $right_limit);
		return $this->db->get('projects')->result();
	}

	public function count_all() {
		return $this->db->get('projects')->num_rows();
	}

	public function create($project) {
		$CI = &get_instance();
		$CI->load->model('navigation_tree');
		// TODO validate fields
		$this->db->trans_start();
		if (!$this->db->insert('projects', $project)) {
			throw new Exception('Failed to create project');
		}
		$navigation_tree_node = new stdClass();
		$navigation_tree_node->node_id = $this->db->insert_id();
		$navigation_tree_node->node_type_id = 1; // FIXME: constants
		$navigation_tree_node->parent_id = 0; // TBD: constants
		try {
			$CI->navigation_tree->create($navigation_tree_node);
			$this->db->trans_commit();
		} catch (Exception $exception) {
			$this->db->trans_rollback();
			throw $exception;
		}
	}

	public function get($id) {
		$this->db->where('id', $id);
		return $this->db->get('projects')->row();
	}

	public function update($project) {
		$this->db->where('id', $project->id);
		$this->db->update('projects', $project);
	}

}

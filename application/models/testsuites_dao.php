<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class TestSuites_Dao extends CI_Model {

	public function all($left_limit = 0, $right_limit = 0) {
		if ($left_limit > 0)
			$this->db->limit($left_limit, $right_limit);
		return $this->db->get('test_suites')->result();
	}

	public function count_all() {
		return $this->db->get('test_suites')->num_rows();
	}

	public function create($testsuite, $parent_id = 0) {
		$CI = &get_instance();
		$CI->load->model('navigation_tree_dao');
		// TODO validate fields
		$this->db->trans_start();
		try {
			if (!$this->db->insert('test_suites', $testsuite)) {
				throw new Exception('Failed to create test suite');
			}
			if (!isset($parent_id) || $parent_id <= 0)
				$parent_id = $testsuite->project_id;
			$navigation_tree_node = new stdClass();
			$navigation_tree_node->node_id = $this->db->insert_id();
			$navigation_tree_node->node_type_id = 2; // FIXME: constants
			$navigation_tree_node->parent_id = $parent_id; // TBD: constants
			$navigation_tree_node->display_name = $testsuite->name;
			
			$CI->navigation_tree_dao->create($navigation_tree_node);
			$this->db->trans_commit();
		} catch (Exception $exception) {
			log_message('error', 'DB error: ' . $msg = $this->db->_error_message() . ', message: ' . $this->db->_error_message());
			$this->db->trans_rollback();
			log_message('error', 'Failed to create test suite: ' . var_export($testsuite, true));
			throw $exception;
		}
	}

	public function get($id) {
		$this->db->where('id', $id);
		return $this->db->get('test_suites')->row();
	}

	public function update($testsuite) {
		$this->db->where('id', $testsuite->id);
		$this->db->update('test_suites', $testsuite);
	}

}

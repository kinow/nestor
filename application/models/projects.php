<?php
class Projects extends CI_Model {
	
	public function all($left_limit = 0, $right_limit = 0) {
			$this->db->limit($left_limit, $right_limit);
		return $this->db->get('projects')->result();
	}
	
	public function count_all() {
		return $this->db->get('projects')->num_rows();
	}
	
	public function create($project) {
		// TODO validate fields
		$this->db->insert('projects', $project);
	}
	
}
<?php
class Projects extends CI_Model {
	
	public function all() {
		return $this->db->get('projects')->result();
	}
	
	public function create($project) {
		// TODO validate fields
		$this->name = $project->name;
		$this->db->insert('projects', $project);
	}
	
}
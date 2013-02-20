<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Projects_Manager {
	
	var $projects_model = NULL;
	
	public function __construct() {
		parent::__construct();
		$this->model->load('projects');
		$this->projects_model = $this->projects;
	}
	
	public function all() {
		return $this->projects_model->all();
	}
	
	public function create($project = NULL) {
		if (!is_null($project)) {
			$this->projects_model->create($project);
		}
	}
	
}

/* End of file projects.php */
/* Location: ./application/logic/projects.php */
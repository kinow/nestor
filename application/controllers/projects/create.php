<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Create extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('projects');
	}
	
	public function index() {
		$this->form_validation->set_rules('name', 'Name', 'required');
		if ($this->form_validation->run()) {
			$name = $this->form_validation->set_value('name');
			$project = new stdClass();
			$project->name = $name;
			$this->projects->create($project);
		} else {
			$this->load->view('projects/create');
		}
	}
}

/* End of file create.php */
/* Location: ./application/controllers/project/create.php */
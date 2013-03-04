<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Create extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('projects');
	}
	
	public function index() {
		$this->theme->set('active', 'projects');
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');
		if ($this->form_validation->run()) {
			$project = new stdClass();
			$project->name = $this->form_validation->set_value('name');
			$project->description = $this->form_validation->set_value('description');
			$this->projects->create($project);
			$this->add_flashdata_message('Project created successfully.', 'success');
			redirect('/projects/');
		} else {
			$this->theme->view('projects/create');
		}
	}
}

/* End of file create.php */
/* Location: ./application/controllers/project/create.php */
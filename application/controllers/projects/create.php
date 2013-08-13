<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Create extends Twiggy_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('projects');
	}
	
	public function index() {
		$projects = $this->projects->all();
		$this->twiggy->set('projects', $projects);
		$this->twiggy->set('active', 'projects');
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');
		if ($this->form_validation->run()) {
			$project = new stdClass();
			$project->name = $this->form_validation->set_value('name');
			$project->description = $this->form_validation->set_value('description');
			try {
				$this->projects->create($project);
			} catch(Exception $exception) {
				$this->add_error($exception->getMessage());
				redirect('/projects/');
			}
			$this->add_success('Project created successfully.');
			redirect('/projects/');
		} else {
			$this->twiggy->display('projects/create');
		}
	}
}


<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class View extends Twiggy_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('projects');
	}
	
	public function index($id) {
		$projects = $this->projects->all();
		$this->twiggy->set('active', 'projects');
		$this->twiggy->set('projects', $projects);
		
		$active_project = $this->session->userdata('active_project');
		$this->twiggy->set('active_project', $active_project);
		
		$this->form_validation->set_rules('id', 'ID', 'required');
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');
		if ($this->form_validation->run()) {
			$project = new stdClass();
			$project->id = $this->form_validation->set_value('id');
			$project->name = $this->form_validation->set_value('name');
			$project->description = $this->form_validation->set_value('description');
			$this->projects->update($project);
			$this->add_success('Project updated successfully');
			redirect('/projects/' . $id);
		} else {
			$project = $this->projects->get($id);
			$this->twiggy->set('project', $project);
			$this->twiggy->display('projects/view');
		}
	}
}

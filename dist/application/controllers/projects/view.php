<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class View extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('projects');
	}
	
	public function index($id) {
		$this->theme->set('active', 'projects');
		$this->form_validation->set_rules('id', 'ID', 'required');
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');
		if ($this->form_validation->run()) {
			$project = new stdClass();
			$project->id = $this->form_validation->set_value('id');
			$project->name = $this->form_validation->set_value('name');
			$project->description = $this->form_validation->set_value('description');
			$this->projects->update($project);
			$this->add_flashdata_message('Project updated successfully.', 'success');
			redirect('/projects/' . $id);
		} else {
			$project = $this->projects->get($id);
			$this->theme->set('project', $project);
			$this->theme->view('projects/view');
		}
	}
}

/* End of file view.php */
/* Location: ./application/controllers/project/view.php */
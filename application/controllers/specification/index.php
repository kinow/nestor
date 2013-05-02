<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('projects');
	}
	
	public function index() {
		$projects = $this->projects->all();
		$this->theme->set('projects', $projects);
		$project = $this->session->userdata('project');
		$this->theme->set('project', $project);
		$this->theme->set('active', 'specification');
		$this->theme->view('specification/index');
	}
}

/* End of file index.php */
/* Location: ./application/controllers/specification/index.php */
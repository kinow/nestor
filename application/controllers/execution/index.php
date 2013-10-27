<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends Twiggy_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('projects');
	}
	
	public function index() {
		// Projects
		$active_project = $this->get_current_project();
		$projects = $this->projects->all();
		// UI
		$this->twiggy->set('active_project', $active_project);
		$this->twiggy->set('projects', $projects);
		$this->twiggy->set('active', 'execution');
		$this->twiggy->display('execution/index');
	}
}

/* End of file index.php */
/* Location: ./application/controllers/execution/index.php */
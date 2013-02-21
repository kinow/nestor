<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class All extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('projects');
	}
	
	public function index() {
		$projects = $this->projects->all();
		$data = array();
		$data['projects'] = $projects;
		$this->load->view('projects/all', $data);
	}
}

/* End of file all.php */
/* Location: ./application/controllers/project/all.php */
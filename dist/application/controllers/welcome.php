<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('projects');
	}
	
	public function index() {
		$projects = $this->projects->all();
		$this->theme->set('projects', $projects);
		$this->theme->view('welcome');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
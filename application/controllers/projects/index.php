<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends MY_Controller {

	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		$this->theme->set('active', 'projects');
		$this->theme->view('projects/index');
	}
}

/* End of file index.php */
/* Location: ./application/controllers/project/index.php */
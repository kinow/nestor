<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends MY_Controller {

	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		$this->theme->set('active', 'requirements');
		$this->theme->view('requirements/index');
	}
}

/* End of file index.php */
/* Location: ./application/controllers/requirements/index.php */
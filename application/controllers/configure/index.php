<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		$this->theme->set('active', 'manage');
		$this->theme->view('configure/index');
	}
}

/* End of file index.php */
/* Location: ./application/controllers/configure/index.php */
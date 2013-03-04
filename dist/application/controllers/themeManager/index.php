<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends MY_Controller {

	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		$this->theme->set('active', 'manage');
		$this->theme->view('themeManager/index');
	}
}

/* End of file index.php */
/* Location: ./application/controllers/themes/index.php */
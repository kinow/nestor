<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Nestor\Nestor;

class Index extends MY_Controller {

	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		$this->theme->set('active', 'manage');
		$nestor = Nestor::get_instance();
		$this->theme->set('themes', $nestor->get_theme_manager()->get_installed_themes());
		$this->theme->view('themeManager/index');
	}
}

/* End of file index.php */
/* Location: ./application/controllers/themeManager/index.php */
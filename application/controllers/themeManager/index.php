<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Nestor\Nestor;

class Index extends MY_Controller {

	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		$this->theme->set('active', 'manage');
		$nestor = Nestor::get_instance();
		$nestor->get_theme_manager()->scan('themes/');
		$themes = $nestor->get_theme_manager()->get_installed_themes();
		$available = $nestor->get_theme_manager()->get_available_themes();
		foreach ($themes as $theme) {
			foreach ($available as $available_theme) {
				if ($theme->name == $available_theme->name) {
					$available_theme->installed = 1;
				} else {
					$available_theme->installed = 0;
				}
			}
		}
		$this->theme->set('themes', $themes);
		$this->theme->set('available', $available);
		$this->theme->view('themeManager/index');
	}
}

/* End of file index.php */
/* Location: ./application/controllers/themeManager/index.php */
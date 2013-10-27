<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Nestor\Nestor;

class Index extends Twiggy_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('projects');
	}
	
	public function index() {
		// Projects
		$active_project = $this->get_current_project();
		$projects = $this->projects->all();
		// Themes
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
		// UI
		$this->twiggy->set('active_project', $active_project);
		$this->twiggy->set('projects', $projects);
		$this->twiggy->set('active', 'manage');
		$this->twiggy->set('themes', $themes);
		$this->twiggy->set('available', $available);
		$this->twiggy->display('themeManager/index');
	}
}

/* End of file index.php */
/* Location: ./application/controllers/themeManager/index.php */
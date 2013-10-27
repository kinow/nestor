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
		// UI
		$this->twiggy->set('active_project', $active_project);
		$this->twiggy->set('projects', $projects);
		$this->twiggy->set('active', 'manage');
		$this->twiggy->display('pluginManager/index');
	}
}

/* End of file index.php */
/* Location: ./application/controllers/pluginManager/index.php */
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends Twiggy_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('projects');
		$this->load->model('navigation_tree_dao');
	}
	
	public function index() {
		$active_project = $this->session->userdata('active_project');
		$this->twiggy->set('active_project', $active_project);
		$projects = $this->projects->all();
		$this->twiggy->set('projects', $projects);
		$this->twiggy->set('active', 'specification');
		$navigation_tree_nodes = $this->navigation_tree_dao->get_by_node_id($active_project->id);
		$this->twiggy->set('navigation_tree_nodes', $navigation_tree_nodes);
		$this->twiggy->display('specification/index');
	}
}

/* End of file index.php */
/* Location: ./application/controllers/specification/index.php */
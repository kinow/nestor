<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends Twiggy_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('projects');
	}
	
	public function index() {
		$projects = $this->projects->all();
		$active_project = $this->session->userdata('active_project');

		$this->twiggy->set('projects', $projects);
		$this->twiggy->set('active_project', $active_project);
		$this->twiggy->display('index');
	}
}

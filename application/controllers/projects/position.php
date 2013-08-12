<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Position extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('projects');
		$this->load->library('user_agent');
	}
	
	public function index() {
		$project_id = $this->input->get('project_id');
		$project = $this->projects->get($project_id);
		var_dump($project);
		if ($project) {
			$this->session->set_userdata('active_project', $project);
		} else {
			$this->session->unset_userdata('active_project');
		}
		$referrer = '/';
		if ($this->agent->is_referral()) {
			$referrer = $this->agent->referrer();
		}
		redirect($referrer);
	}
}

/* End of file position.php */
/* Location: ./application/controllers/project/position.php */
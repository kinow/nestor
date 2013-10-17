<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TestSuite extends Twiggy_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('projects');
		$this->load->model('navigation_tree_dao');
		$this->load->model('testsuites_dao');
	}
	
	public function index() {
		$this->form_validation->set_rules('project_id', 'Project ID', 'required|trim|xss_clean|numeric');
		$this->form_validation->set_rules('node_id', 'Node ID', 'required|trim|xss_clean|numeric');
		$this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean|min_length[3]');
		$this->form_validation->set_rules('description', 'Description', 'required|trim|xss_clean|min_length[3]');
		if ($this->form_validation->run()) {
			$testsuite = new stdClass();
			$testsuite->project_id = $this->form_validation->set_value('project_id');
			$testsuite->name = $this->form_validation->set_value('name');
			$testsuite->description = $this->form_validation->set_value('description');
			$node_id = $this->form_validation->set_value('node_id');
			
			$this->testsuites_dao->create($testsuite, $node_id);
			
			redirect(sprintf("/specification?node_id=%d", $node_id));
		} else {
			$node_id = $this->form_validation->set_value('node_id');
			$this->session->set_flashdata('errors', validation_errors('', ''));
			if (isset($node_id) && $node_id) {
				redirect(sprintf("/specification?node_id=%d", $node_id));
			} else {
				redirect('/specification');
			}
		}
	}
}

/* End of file testsuite.php */
/* Location: ./application/controllers/specification/testsuite.php */
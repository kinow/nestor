<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TestCase extends Twiggy_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('projects');
		$this->load->model('navigation_tree_dao');
		$this->load->model('testcases_dao');
	}
	
	public function index() {
		$this->form_validation->set_rules('project_id', 'Project ID', 'required|trim|xss_clean|numeric');
		$this->form_validation->set_rules('test_suite_id', 'Test Suite ID', 'required|trim|xss_clean|numeric');
		$this->form_validation->set_rules('node_id', 'Node ID', 'required|trim|xss_clean|numeric');
		$this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean|min_length[3]');
		$this->form_validation->set_rules('description', 'Description', 'required|trim|xss_clean|min_length[3]');
		$this->form_validation->set_rules('execution_type_id', 'Execution Type ID', 'required|trim|xss_clean|numeric');
		if ($this->form_validation->run()) {
			$testcase = new stdClass();
			$testcase->project_id = $this->form_validation->set_value('project_id');
			$testcase->test_suite_id = $this->form_validation->set_value('test_suite_id');
			$testcase->name = $this->form_validation->set_value('name');
			$testcase->description = $this->form_validation->set_value('description');
			$testcase->execution_type_id = $this->form_validation->set_value('execution_type_id');

			$node_id = $this->form_validation->set_value('node_id');
			
			$this->testcases_dao->create($testcase, $node_id);
			
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

/* End of file testcase.php */
/* Location: ./application/controllers/specification/testcase.php */
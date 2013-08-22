<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';

class Nodes extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('navigation_tree_dao');
	}
	
	public function index_get() {
		$nodes = $this->navigation_tree_dao->all();
		return $this->response($nodes);
	}
	
	public function id_get() {
		$node_id = $this->input->get('node_id');
		$nodes = array();
		if (isset($node_id) && $node_id) {
			$nodes = $this->navigation_tree_dao->get_by_node_id($node_id);
		}
		return $this->response($nodes);
	}
}

/* End of file navigation_tree.php */
/* Location: ./application/controllers/specification/navigation_tree.php */
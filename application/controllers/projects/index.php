<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends Twiggy_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('projects');
	}
	
	public function index() {
		$this->load->library('pagination');
		$per_page = 5;
		$page = $this->input->get('page', TRUE);
		if(!isset($page) || empty($page))
			$page = 0;
		else
			$page = ($page - 1) * $per_page;
		$projects = $this->projects->all($per_page, $page);
		// config the pagination
		$config = array();
		$config['base_url'] = base_url('projects');
		$config['total_rows'] = $this->projects->count_all();
		$config['per_page'] = $per_page;
		$config['num_links'] = 9;
		$config['uri_segment'] = 2;
		$config['use_page_numbers'] = TRUE;
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'page';
		$config['display_pages'] = TRUE;
		
		$config['full_tag_open'] = '<div class="pagination"><ul>';
		$config['full_tag_close'] = '</ul></div><!--pagination-->';
		
		$config['first_link'] = '&laquo; First';
		$config['first_tag_open'] = '<li class="prev page">';
		$config['first_tag_close'] = '</li>';
		
		$config['last_link'] = 'Last &raquo;';
		$config['last_tag_open'] = '<li class="next page">';
		$config['last_tag_close'] = '</li>';
		
		$config['next_link'] = 'Next &rarr;';
		$config['next_tag_open'] = '<li class="next page">';
		$config['next_tag_close'] = '</li>';
		
		$config['prev_link'] = '&larr; Previous';
		$config['prev_tag_open'] = '<li class="prev page">';
		$config['prev_tag_close'] = '</li>';
		
		$config['cur_tag_open'] = '<li class="active"><a href="">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li class="page">';
		$config['num_tag_close'] = '</li>';
		
		$this->pagination->initialize($config);
		
		$this->twiggy->set('active', 'projects');
		$this->twiggy->set('projects', $projects);
		$this->twiggy->set('pagination', $this->pagination);
		//$this->theme->view('projects/index');

		$active_project = $this->session->userdata('active_project');
		$this->twiggy->set('active_project', $active_project);
		$this->twiggy->display('projects/index');
	}
}

/* End of file all.php */
/* Location: ./application/controllers/project/all.php */
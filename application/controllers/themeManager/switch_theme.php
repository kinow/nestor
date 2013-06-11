<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Switch_Theme extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index($theme) {
		if ($theme == null) {
			$this->add_flashdata_message('Invalid theme');
			redirect('/');
		}
		$this->get_nestor()->get_theme_manager()->set_active($theme);
		redirect('themeManager/');		
	}
	
}

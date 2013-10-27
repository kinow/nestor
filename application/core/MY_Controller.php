<?php  
use Nestor\Nestor;

if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller for themes. 
 */
class MY_Controller extends CI_Controller {
	
	var $js = array();
	
    public function __construct() {
        parent::__construct();
        
        $ci = & get_instance();
        $this->ci = $ci;
        $this->nestor = new Nestor($ci);
        
    	//load theme spark
		$this->load->spark('theme/1.0.0');
		
		//try to get the theme from the cookie
		$ci->load->model('themes_model');
		$theme = $ci->themes_model->get_active();
		if (is_null($theme) || !$theme) {
			$theme = 'default';
		}
		$this->theme->set_theme($theme->name);
		
		$messages_flash = $this->session->flashdata('messages');
		if (isset($messages_flash) && is_array($messages_flash)) {
			foreach ($messages_flash as $message_flash) {
				$this->theme->add_message($message_flash['message'], $message_flash['type']);
			}
		}
		
		$messages_user  = $this->session->userdata('messages');
		if (isset($messages_user) && is_array($messages_user)) {
			foreach ($messages_user as $message_user) {
				$this->theme->add_message($message_user['message'], $message_user['type']);
			}
		}
		
		$this->theme->set('project', $this->session->userdata('project'));
    }
    
    protected function add_flashdata_message($message, $type = 'info') {
    	if (!is_array($message)) {
    		$messages = $this->session->flashdata('messages');
    		$messages[] = array(
	    		'message' => $message,
	    		'type'    => $type,
			);
    		$this->session->set_flashdata('messages', $messages);
    	}
    }
    
    protected function add_userdata_message($message, $type = 'info') {
    	if (!is_array($message)) {
    		$messages = $this->session->userdata('messages');
    		$messages[] = array(
	    		'message' => $message,
	    		'type'    => $type,
			);
    		$this->session->set_userdata('messages', $messages);
    	}
    }
    
    protected function get_nestor() {
    	return $this->nestor;
    }
    
    protected function get_current_project() {
    	$active_project = $this->session->userdata('active_project');
    	if (!isset($active_project) || is_null($active_project) || !$active_project) {
    		$this->add_flashdata_message('Please, select a project first', 'warning');
    		redirect('/');
    	}
    	return $active_project;
    }

}

class Twiggy_Controller extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        
        global $BM;
        
        $this->twiggy->title('Nestor QA');
        // PHP functions
        $this->twiggy->register_function('array_merge');
        $this->twiggy->register_function('sprintf');
        $this->twiggy->register_function('print_r');
        $this->twiggy->register_function('var_dump');
        $this->twiggy->register_function('empty');
        // CI functions
        $this->twiggy->register_function('anchor');
        $this->twiggy->register_function('site_url');
        $this->twiggy->register_function('current_url');
        $this->twiggy->register_function('base_url');
        $this->twiggy->register_function('uri_string');
        $this->twiggy->register_function('validation_errors');
        $this->twiggy->register_function('form_open');
        $this->twiggy->register_function('form_open_multipart');
        $this->twiggy->register_function('form_label');
        $this->twiggy->register_function('form_input');
        $this->twiggy->register_function('form_checkbox');
        $this->twiggy->register_function('form_dropbox');
        $this->twiggy->register_function('form_password');
        $this->twiggy->register_function('form_hidden');
        $this->twiggy->register_function('form_radio');
        $this->twiggy->register_function('form_textarea');
        $this->twiggy->register_function('form_multiselect');
        $this->twiggy->register_function('form_dropdown');
        $this->twiggy->register_function('form_submit');
        $this->twiggy->register_function('form_close');
        $this->twiggy->register_function('set_value');
        $this->twiggy->register_function('elapsed_time');
        // Theme Spark functions
        $this->twiggy->register_function('bootstrap_menus');
        $this->twiggy->register_function('bootstrap_messages');
        // Twiggy
        $this->twiggy->register_function('twiggy_theme_url');
        // Nestor functions
        $this->twiggy->register_function('nestor_version');
        $this->twiggy->register_function('print_navigation_tree');
        
        $this->twiggy->set('errors', $this->session->flashdata('errors'));
		$this->twiggy->set('warning', $this->session->flashdata('warning'));
		$this->twiggy->set('success', $this->session->flashdata('success'));
		
        $elapsed = $BM->elapsed_time('total_execution_time_start', 'total_execution_time_end');
        $this->twiggy->set('elapsed_time', $elapsed);
        
        $this->form_validation->set_error_delimiters('<div class="alert alert-block alert-error fade in" data-dismiss="alert"><button type="button" class="close" data-dismiss="alert">×</button>', '</div>');
        
        $this->twiggy->set('js', $this->js);
        $this->twiggy->set('active_project', NULL);
        $this->twiggy->set('projects', NULL);
    }
    
    function add_error($errors) {
    	$this->session->set_flashdata('errors', $errors);
    }
    
    function add_warning($messages) {
    	$this->session->set_flashdata('warning', $messages);
    }
    
    function add_success($messages) {
    	$this->session->set_flashdata('success', $messages);
    }
    
}
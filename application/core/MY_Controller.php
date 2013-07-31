<?php  
use Nestor\Nestor;

if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller for themes. 
 */
class MY_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        
        $ci = & get_instance();
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

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */
<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Controller for themes. 
 */
class MY_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
    	//load theme spark
		$this->load->spark('theme/1.0.0');
		
		//try to get the theme from the cookie
		$theme = get_cookie('theme');
		if (in_array($theme, array('default', 'skeleton')))
		{
			//got a valid theme... set it
			$this->theme->set_theme($theme);
		}
    }
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * NestorQA
 * 
 * Test management system.
 * 
 * @package NestorQA
 * @author NestorQA team
 * @copyright Copyright (c) 2012 - 2013, NestorQA team
 * @license MIT
 * @link http://nestor-qa.org
 * @since 0.0.2
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * NestorQA Helpers
 *
 * @package		NestorQA
 * @subpackage	Helpers
 * @category	Helpers
 * @author NestorQA team
 * @link http://nestor-qa.org
 */

// ------------------------------------------------------------------------

/**
 * Version
 * 
 * Displays product version
 * 
 * @access public
 * @return string
 */
if ( ! function_exists('nestor_version')) {
	function nestor_version() {
		$ci =& get_instance();
		$ci->config->load('nestor');
		$version = $ci->config->item('nestor_version');
		if ($version) 
			return $version;
		return 'N/A';
	}
}

/* End of file nestor_helper.php */
/* Location: ./application/helpers/nestor_helper.php */

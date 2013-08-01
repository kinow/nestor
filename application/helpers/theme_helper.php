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
 * @since 0.0.4
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Theme Helpers
 *
 * @package		NestorQA
 * @subpackage	Helpers
 * @category	Helpers
 * @author NestorQA team
 * @link http://nestor-qa.org
 */

// ------------------------------------------------------------------------

/**
 * Twiggy Theme URL
 *
 * Create a local URL based on your theme and your basepath. Segments can be passed via the
 * first parameter either as a string or an array.
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('twiggy_theme_url'))
{
    function twiggy_theme_url($uri = '')
    {
        $CI =& get_instance();
        //$theme_url =$CI->config->item('theme', 'url');
        $theme_url = 'themes/default/'; // FIXME: get theme url from Theme Spark config
        return $CI->config->site_url($theme_url . $uri);
    }
}
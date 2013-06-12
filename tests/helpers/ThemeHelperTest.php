<?php

/**
 * @group helpers 
 */
class ThemeHelperTest extends CIUnit_TestCase {
	
	public function setUp() {
		$this->CI->load->helper('theme');
	}
	
	public function testGetTheme() {
// 		$theme = get_theme('super');
// 		$this->assertEquals('default', $theme); //$this->assertEquals('Hi!', say('Hi!'));
	}
	
	public function testThemeUrl() {
// 		$theme_url = theme_url('assets/css/blueprint.css');
//  		$this->assertEquals('http://localhost/app/themes/default/assets/css/blueprint.css', $theme_url);
	}
	
}

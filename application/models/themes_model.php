<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * The _Model suffix is used to avoid conflict with themes (ThemeManager) loaded by the core.
 * @since 0.0.4
 */
class Themes_Model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	function all($left_limit = 0, $right_limit = 0) {
		if ($left_limit > 0)
			$this->db->limit($left_limit, $right_limit);
		return $this->db->get('themes')->result();
	}
	
	public function count_all() {
		return $this->db->get('themes')->num_rows();
	}
	
	public function create($theme) {
		$this->db->insert('themes', $theme);
	}
	
	public function get($id) {
		$this->db->where('id', $id);
		return $this->db->get('themes')->row();
	}
	
	public function get_by_name($name) {
		$this->db->where('name', $name);
		return $this->db->get('themes')->row();
	}
	
	public function update($theme) {
		$this->db->where('id', $theme->id);
		$this->db->update('themes', $theme);
	}
	
	public function remove($theme) {
		$this->db->where('id', $theme->id);
		$this->db->delete('themes');
	}
	
}

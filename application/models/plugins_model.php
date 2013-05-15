<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * The _Model suffix is used to avoid conflict with plugins (PluginManager) loaded by the core.
 * @since 0.0.4
 */
class Plugins_Model extends CI_Model {
	
	function all($left_limit = 0, $right_limit = 0) {
		if ($left_limit > 0)
			$this->db->limit($left_limit, $right_limit);
		return $this->db->get('plugins')->result();
	}
	
	public function count_all() {
		return $this->db->get('plugins')->num_rows();
	}
	
	public function create($plugin) {
		$this->db->insert('plugins', $plugin);
	}
	
	public function get($id) {
		$this->db->where('id', $id);
		return $this->db->get('plugins')->row();
	}
	
	public function update($plugin) {
		$this->db->where('id', $plugin->id);
		$this->db->update('plugins', $plugin);
	}
	
}

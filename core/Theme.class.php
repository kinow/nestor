<?php

namespace Nestor;

/**
 * A theme.
 * 
 * @since 0.4
 */
abstract class Theme {
	/**
	 * ID.
	 * @var number
	 */
	protected $id;
	/**
	 * Name.
	 * @var string
	 */
	protected $name;
	/**
	 * Description.
	 * @var string
	 */
	protected $description;
	/**
	 * URL.
	 * @var string
	 */
	protected $url;
	/**
	 * Author.
	 * @var string
	 */
	protected $author;
	/**
	 * Author's website.
	 * @var string
	 */
	protected $author_url;
	/**
	 * Version.
	 * @var string
	 */
	protected $version;
	/**
	 * Status.
	 * @var number
	 */
	protected $status;
	
	public function __construct() {
		
	}
	
	public function get_author() {
		return $this->author;
	}
	
	public function set_author($author) {
		$this->author = $author;
	}
	
	public function get_author_url() {
		return $this->author_url;
	}
	
	public function get_author_url($author_url) {
		$this->author_url = $author_url;
	}
	
	public function get_description() {
		return $this->description;
	}
	
	public function set_description($description) {
		$this->description = $description;
	}
	
	public function get_id() {
		return $this->id;
	}
	
	public function set_id($id) {
		$this->id = $id;
	}
	
	public function get_name() {
		return $this->name;
	}
	
	public function set_name($name) {
		$this->name = $name;
	}
	
	public function get_status() {
		return $this->status;
	}
	
	public function set_status($status) {
		$this->status = $status;
	}
	
	public function get_url() {
		return $this->url;
	}
	
	public function set_url($url) {
		$this->url = $url;
	}
	
	public function get_version() {
		return $this->version;
	}
	
	public function set_version($version) {
		$this->version = $version;
	}
	
	public function __toString() {
		return $this->getName() . '-' . $this->get_version();
	}
}

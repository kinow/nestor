<?php namespace Nestor\Model;

interface Copyable {

	/**
	 * Copies this instance to a new one.
	 *
	 * @param $from the origin name or instance
	 * @param $to the destination name or instance
	 * @return void
	 * @throws Exception if it failed to copy the object
	 */
	function copy($from, $to);

}
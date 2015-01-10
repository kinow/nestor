<?php namespace Nestor\Model;

/** 
 * A UI editor.
 */
interface Editor {

	public function getName();

	public function render($name, $value, array $options);

}

<?php namespace Kinow\WysiwygEditor;

use Nestor\Model\Editor;

class WysiwygEditor implements Editor {

	public function getName()
	{
		return "WYSIWYG Editor";
	}

	public function render($name, $value, array $options)
	{
		$str = \View::make('kinow/wysiwyg-editor::editor')->render();
		$editorUI = \Theme::twigy($str, array('name' => $name, 'value' => $value, 'options' => $options));
		return $editorUI;
	}

}

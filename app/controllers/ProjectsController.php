<?php

class ProjectsController extends BaseController {

	public function showAll() {
		$theme = Theme::uses('default')->layout('default');
		$theme->setActive('projects');
		return $theme->scope('home.index')->render();
	}

}
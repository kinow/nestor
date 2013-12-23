<?php

use Theme;
use Nestor\Repositories\ProjectRepository;

class ProjectsController extends BaseController {

	/**
	 * The project repository implementation.
	 *
	 * @var Nestor\Repositories\ProjectRepository
	 */
	protected $projects;

	public function __construct(ProjectRepository $projects)
	{
		$this->projects = $projects;
	}

	public function getIndex()
	{
		$theme = Theme::uses('default')->layout('default');
		$theme->setActive('projects');
		return $theme->scope('project.index')->render();
	}

}
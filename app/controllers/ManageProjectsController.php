<?php

use Nestor\Repositories\ProjectRepositoryInterface;

class ManageProjectsController extends BaseController 
{

	/**
	 * Project repository.
	 *
	 * @var Nestor\Repositories\ProjectRepositoryInterface
	 */
	protected $projects;

	public function __construct(ProjectRepositoryInterface $projects)
	{
		parent::__construct();
		$this->projects = $projects;
		$this->theme->setActive('manage');
		$this->beforeFilter('@isAuthenticated');
	}

	public function getIndex() {
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Manage Nestor', URL::to('/manage'))->
			add('Manage Projects');
		$args = array();
		$projects = $this->projects->paginateWith(10, array('projectStatus'));
		$args['projects'] = $projects;
		return $this->theme->scope('manage.project.index', $args)->render();
	}

}
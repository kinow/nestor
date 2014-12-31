<?php

use Nestor\Repositories\ProjectRepository;
use Nestor\Repositories\ProjectStatusRepository;
use Nestor\Model\ProjectStatus;

class ManageProjectsController extends \BaseController 
{

	/**
	 * Project repository.
	 *
	 * @var Nestor\Repositories\ProjectRepository
	 */
	protected $projects;

	/**
	 * Project statuses repository.
	 *
	 * @var Nestor\Repositories\ProjectStatusRepository
	 */
	protected $projectStatuses;

	public $restful = true;

	public function __construct(ProjectRepository $projects, ProjectStatusRepository $projectStatuses)
	{
		parent::__construct();
		$this->projects = $projects;
		$this->projectStatuses = $projectStatuses;
		$this->theme->setActive('manage');
		$this->beforeFilter('@isAuthenticated');
	}

	public function index() {
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Manage Nestor', URL::to('/manage'))->
			add('Manage Projects');
		$args = array();
		$projects = $this->projects->paginateWith(10, array('projectStatus'));
		$args['projects'] = $projects;
		return $this->theme->scope('manage.project.index', $args)->render();
	}

	public function show($id) {
		$project = $this->projects->findWith($id, array('projectStatus'));
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Manage Nestor', URL::to('/manage'))->
			add('Manage Projects', URL::to('/manage/projects'))->
			add(sprintf('Project %s', $project['name']));
		$args = array();
		$args['project'] = $project;
		return $this->theme->scope('manage.project.show', $args)->render();
	}

	public function edit($id)
	{
		$project = $this->projects->findWith($id, array('projectStatus'));
		$projectStatuses = $this->projectStatuses->all();
		$selectProjectStatuses = array(); // for use with HTML select
		foreach ($projectStatuses as $projectStatus) {
			$selectProjectStatuses[$projectStatus->id] = $projectStatus->name;
		}
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Manage Nestor', URL::to('/manage'))->
			add('Manage Projects', URL::to('/manage/projects'))->
			add(sprintf('Project %s', $project['name']));
		$args = array();
		$args['project'] = $project;
		$args['projectStatuses'] = $selectProjectStatuses;
		return $this->theme->scope('manage.project.edit', $args)->render();
	}

	public function update($id)
	{
		$updated = $this->projects->update(
			$id,
			array(
				'name' => Input::get('name'),
				'description' => Input::get('description'),
				'project_statuses_id' => Input::get('project_statuses_id')
			)
		);
		$currentProject = $this->theme->get('current_project');
		if ($currentProject && $currentProject['id'] == $id) {
			if (Input::get('project_statuses_id') == projectStatus::INACTIVE) {
				Session::forget('current_project');
			}
		}
		return Redirect::to(URL::to('manage/projects/' . $id . '/'))
			->with('success', 'The project was updated');
	}

	public function destroy($id) 
	{
		$project = $this->projects->findWith($id, array('projectStatus'));
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Manage Nestor', URL::to('/manage'))->
			add('Manage Projects', URL::to('/manage/projects'))->
			add(sprintf('Project %s', $project['name']));
		$args = array();
		$args['project'] = $project;
		return $this->theme->scope('manage.project.delete', $args)->render();
	}

	public function confirmDestroy($id) 
	{
		$this->projects->delete($id);
		return Redirect::to(URL::to('/manage/projects'))
			->with('success', 'The project was deleted');
	}

}
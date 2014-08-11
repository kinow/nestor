<?php

use Nestor\Repositories\ProjectRepositoryInterface;
use Nestor\Repositories\NavigationTreeRepository;
use Nestor\Util\ValidationException;

class ProjectsController extends \BaseController 
{
	/**
	 * Project repository.
	 *
	 * @var Nestor\Repositories\ProjectRepositoryInterface
	 */
	protected $projects;
	/**
	 * Navigation tree node repository.
	 *
	 * @var Nestor\Repositories\NavigationTreeRepository
	 */
	protected $nodes;
	/**
	 * UI Theme.
	 */
	protected $theme;

	public $restful = true;

	public function __construct(ProjectRepositoryInterface $projects, NavigationTreeRepository $nodes)
	{
		parent::__construct();
		$this->projects = $projects;
		$this->nodes = $nodes;
		$this->beforeFilter('@isAuthenticated');
		$this->theme->setActive('projects');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Projects');
		$args = array();
		$projects = $this->projects->paginateWith(10, array('projectStatus'));
		$args['projects'] = $projects;
		return $this->theme->scope('project.index', $args)->render();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Projects', URL::to('/projects'))->
			add('Create new project');
		return $this->theme->scope('project.create')->render();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		DB::beginTransaction();
		try {
			$project = $this->projects->create(Input::all());
			// FIXME: insert into tree!
			// 		$navigationTreeNode = $this->nodes->create(
			// 				'1-' . $pdo->lastInsertId(),
			// 				'1-' . $pdo->lastInsertId(),
			// 				$pdo->lastInsertId(),
			// 				1,
			// 				$project->name
			// 		);
			DB::commit();
		} catch (ValidationException $ve) {
			DB::rollback();
			return Redirect::to(URL::previous())->withInput()->withErrors($ve->getErrors());
		} catch (Exception $e) {
			DB::rollback();
			throw $e;
		}

		// auto position project
		if (Input::get('position') == 'true') {
			Session::put('current_project', serialize($project));
		}

		return Redirect::to('/projects/')
			->with('success', sprintf('Project %s created', Input::get('name')));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$project = $this->projects->findWith($id, array('projectStatus'));
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Projects', URL::to('/projects'))->
			add(sprintf('Project %s', $project['name']));
		$args = array();
		$args['project'] = $project;
		return $this->theme->scope('project.show', $args)->render();
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$project = $this->projects->find($id);
		$settings = Config::get('settings');
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Projects', URL::to('/projects'))->
			add(sprintf('Project %s', $project['name']));
		$args = array();
		$args['project'] = $project;
		return $this->theme->scope('project.edit', $args)->render();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$project = null;
		$navigationTreeNode = null;
		Log::info('Updating project...');
		$pdo = null;
		try {
			$pdo = DB::connection()->getPdo();
			$pdo->beginTransaction();
			$project = $this->projects->update(
							$id,
							Input::get('name'),
							Input::get('description'),
							1);
			if ($project->isValid() && $project->isSaved())
			{
				$navigationTreeNode = $this->nodes->find('1-'.$project->id, '1-'.$project->id);
				$navigationTreeNode->display_name = $project->name;
				$navigationTreeNode = $this->nodes->update(
						'1-'.$project->id,
						'1-'.$project->id,
						$navigationTreeNode->node_id,
						$navigationTreeNode->node_type_id,
						$navigationTreeNode->display_name);
				$pdo->commit();
			}
		} catch (\PDOException $e) {
			if (!is_null($pdo))
				$pdo->rollBack();
			return Redirect::to('/specification/')
				->withInput();
		}

		if ($project->isSaved())
		{
			return Redirect::route('projects.show', $id)
				->with('success', 'The project was updated');
		} else {
			return Redirect::route('projects.edit', $id)
				->withInput()
				->withErrors($project->errors());
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy2($id)
	{
		$project = null;
		$navigationTreeNode = null;
		Log::info('Deleting project...');
		$pdo = null;
		try {
			$pdo = DB::connection()->getPdo();
			$pdo->beginTransaction();
			$project = $this->projects->find($id);
			$this->projects->delete($id);
			$navigationTreeNode = $this->nodes->find('1-' . $project->id, '1-' . $project->id);
			$this->nodes->deleteWithAllChildren($navigationTreeNode->ancestor, $navigationTreeNode->descendant);
			$pdo->commit();

			$currentProject = $this->theme->get('current_project');
			if ($currentProject && $currentProject->id == $id)
			{
				Session::forget('current_project');
			}
		} catch (\PDOException $e) {
			if (!is_null($pdo))
				$pdo->rollBack();
			return Redirect::to('/specification/')
				->withInput();
		}

		return Redirect::route('projects.index')
			->with('flash', sprintf('The project %s has been deleted', $project->name));
	}

	/**
	 * Deactivates the project.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$project = null;
		$navigationTreeNode = null;
		Log::info('Deactivating project...');
		$pdo = null;
		try {
			$pdo = DB::connection()->getPdo();
			$pdo->beginTransaction();
			$project = $this->projects->deactivate($id);
			$pdo->commit();

			$currentProject = $this->theme->get('current_project');
			if ($currentProject && $currentProject->id == $id)
			{
				Session::forget('current_project');
			}
		} catch (\PDOException $e) {
			if (!is_null($pdo))
				$pdo->rollBack();
			return Redirect::to('/specification/')
				->withInput();
		}

		return Redirect::route('projects.index')
			->with('success', sprintf('The project %s has been deleted', $project->name));
	}

	public function position()
	{
		$project_id = Input::get('project_id');
		try {
			$project = $this->projects->find($project_id);
			Session::put('current_project', serialize($project));
			return Redirect::back();
		} catch (Exception $e) {
			Session::forget('current_project');
			return Redirect::back();
		}
	}

}
<?php

use Theme;
use Input;
use DB;
use Nestor\Repositories\ProjectRepository;
use Nestor\Repositories\NavigationTreeRepository;

class ProjectsController extends \BaseController {

	/**
	 * The project repository implementation.
	 *
	 * @var Nestor\Repositories\ProjectRepository
	 */
	protected $projects;

	/**
	 * The navigation tree node repository implementation.
	 *
	 * @var Nestor\Repositories\NavigationTreeRepository
	 */
	protected $nodes;

	protected $theme;

	public $restful = true;

	public function __construct(ProjectRepository $projects, NavigationTreeRepository $nodes)
	{
		parent::__construct();
		$this->projects = $projects;
		$this->nodes = $nodes;
		$this->theme->setActive('projects');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$args = array();
		$args['projects'] = $this->projects->all();
		return $this->theme->scope('project.index', $args)->render();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return $this->theme->scope('project.create')->render();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$project = null;
		$navigationTreeNode = null;
		Log::info('Creating project...');
		try {
    		DB::connection()->getPdo()->beginTransaction();
			$project = $this->projects->create(
					Input::get('name'),
					Input::get('description'),
					1
			);
			$navigationTreeNode = $this->nodes->create(
				$project->id,
				1,
				null,
				$project->name
			);
			DB::connection()->getPdo()->commit();
		} catch (\PDOException $e) {
			return Redirect::to('/projects/create')
	 			->withInput();
		}
		if ($project->isSaved() && $navigationTreeNode->isSaved())
		{
			return Redirect::to('/projects/')
				->with('flash', 'A new project has been created');
		} else {
			return Redirect::to('/projects/create')
				->withInput()
				->withErrors($project->errors());
		}
// 		return Redirect::to('/projects/create')
// 			->withInput();

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$args = array();
		$args['project'] = $this->projects->find($id);
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
		$args = array();
		$args['project'] = $this->projects->find($id);
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
		$project = $this->projects->update($id, Input::get('name'),
							Input::get('description'),
							1);

		if ($project->isSaved())
		{
			return Redirect::route('projects.show', $id)
				->with('flash', 'The project was updated');
		}

		Redirect::route('projects.edit', $id)
			->withInput()
			->withErrors($project->errors());
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$project = $this->projects->find($id);
		$this->projects->delete($id);
		return Redirect::route('projects.index')
			->with('flash', sprintf('The project %s has been deleted', $project->name));
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
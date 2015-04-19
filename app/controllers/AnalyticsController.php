<?php

class AnalyticsController extends BaseController 
{

	protected $theme;

	public $restful = FALSE;

	public function __construct()
	{
		parent::__construct();
		$this->beforeFilter('@isAuthenticated');
		$this->theme->setActive('analytics');

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
			add('Analytics');
		$args = array();
		return $this->theme->scope('analytics.index', $args)->render();
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
		$project = HMVC::post('api/v1/projects/', Input::all());

		if (!$project || (isset($project['code']) && $project['code'] != 200)) {
			return Redirect::to(URL::previous())->withInput()->withErrors($project['description']);
		}

		// auto position project
		if (Input::get('position') == 'true' && isset($project['id'])) {
			Session::put('current_project', serialize($project));
		}

		return Redirect::to('/projects/')
			->with('success', sprintf('Project %s created', $project['name']));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$project = HMVC::get("api/v1/projects/$id");
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
		$project = HMVC::get("api/v1/projects/$id");
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
		$project = HMVC::put("api/v1/projects/$id", Input::all());

		if (!$project || (isset($project['code']) && $project['code'] != 200)) {
			return Redirect::to(URL::previous())->withInput()->withErrors($project['description']);
		}

		return Redirect::route('projects.show', $id)
			->with('success', sprintf('The project %s was updated', $project['name']));
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
		$projectId = Input::get('project_id');
		$response = HMVC::post("api/v1/projects/position/$projectId");
		return Redirect::back();
	}

}
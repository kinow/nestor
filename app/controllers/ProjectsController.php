<?php

use Theme;
use Input;
use Nestor\Repositories\ProjectRepository;

class ProjectsController extends \BaseController {

	/**
	 * The project repository implementation.
	 *
	 * @var Nestor\Repositories\ProjectRepository
	 */
	protected $projects;

	protected $theme;

	public function __construct(ProjectRepository $projects)
	{
		$this->projects = $projects;
		$this->theme = Theme::uses('default')->layout('default');
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
		Log::info('Validating project params...');
		$messages = $this->projects->validForCreation(
				Input::get('name'),
				Input::get('description'),
				1 // FIXME: 1 will be active?
		);
		if (count($messages) > 0)
		{
			Log::info('Invalid params. Redirecting back...');
			return Redirect::back()
				->withInput()
				->withErrors($messages)
				->with('install_errors', true);
		}

		Log::info('Creating project...');
		$project = $this->projects->create(
				Input::get('name'),
				Input::get('description'),
				1
		);

		return Redirect::to('/projects/' . $project->id);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		print_r($id);
		exit;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
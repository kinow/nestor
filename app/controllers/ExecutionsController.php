<?php

class ExecutionsController extends NavigationTreeController 
{

	protected $theme;

	public $restful = true;

	public function __construct()
	{
		parent::__construct();
		$this->theme->setActive('execution');
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
			add('Execution');
		$args = array();
		$projectId = $this->getCurrentProjectId();
		$testPlans = HMVC::get("api/v1/projects/$projectId/testplans");
		$args['testplans'] = $testPlans;
		return $this->theme->scope('execution.index', $args)->render();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		// FIXME: throw not implemented
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$testRun = HMVC::post('api/v1/executions/', Input::all());

		if (!$testRun || (isset($testRun['code']) && $testRun['code'] != 200)) {
			return Redirect::to(URL::previous())->withInput()->withErrors($testRun['description']);
		}

		return Redirect::to(sprintf('/execution/testruns?test_plan_id=%s', $testRun['test_plan_id']))
			->with('success', sprintf('Test Run %s created', $testRun['name']));
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
		$testplan = $this->testplans->find($id);
		$args['testplan'] = $testplan;
		$args['testcases'] = $testplan->testcases;
		$queries = DB::getQueryLog();
		$last_query = end($queries);
		return $this->theme->scope('testplan.show', $args)->render();
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
		$args['testplan'] = $this->testplans->find($id);
		$args['project'] = $this->getCurrentProject();
		return $this->theme->scope('testplan.edit', $args)->render();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		Log::info('Updating test plan...');

		$testplan = $this->testplans->update(
				$id,
				Input::get('project_id'),
				Input::get('name'),
				Input::get('description')
		);

		if ($testplan->isValid() && $testplan->isSaved())
		{
			return Redirect::route('testplans.show', $id)
				->with('success', 'The test plan was updated');
		} else {
			return Redirect::route('testplans.edit', $id)
				->withInput()
				->withErrors($testplan->errors());
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Log::info('Destroying test plan...');
		$testplan = $this->testplans->find($id);
		$this->testplans->delete($id);

		return Redirect::route('testplans.index')
			->with('success', sprintf('The test plan %s has been deleted', $testplan->name));
	}

}
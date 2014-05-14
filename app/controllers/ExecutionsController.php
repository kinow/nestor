<?php

use \Theme;
use \Input;
use \DB;
use Nestor\Repositories\TestPlanRepository;
use Nestor\Repositories\TestRunRepository;
use Nestor\Repositories\NavigationTreeRepository;

class ExecutionsController extends \NavigationTreeController {

	/**
	 * The test plan repository implementation.
	 *
	 * @var Nestor\Repositories\TestPlanRepository
	 */
	protected $testplans;

	/**
	 * The test run repository implementation.
	 *
	 * @var Nestor\Repositories\TestRunRepository
	 */
	protected $testruns;

	/**
	 * @var Nestor\Repositories\NavigationTreeRepository
	 */
	protected $nodes;

	protected $theme;

	public $restful = true;

	public function __construct(TestPlanRepository $testplans, TestRunRepository $testruns, NavigationTreeRepository $nodes)
	{
		parent::__construct();
		$this->testplans = $testplans;
		$this->testruns = $testruns;
		$this->nodes = $nodes;
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
			add('Test Execution');
		$args = array();
		$project = $this->getCurrentProject();
		$projectId = $project->id;
		$args['testplans'] = $this->testplans->findForExecutionByProjectId($projectId);
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
		Log::info('Creating test run...');

		$testrun = $this->testruns->create(
				Input::get('test_plan_id'),
				Input::get('name'),
				Input::get('description')
		);

		if ($testrun->isValid() && $testrun->isSaved())
		{
			return Redirect::to('/execution/testruns?test_plan_id=' . $testrun->testplan()->id)
				->with('success', 'A new test run has been created');
		} else {
			return Redirect::to('/execution/create?test_plan_id=' . Input::get('test_plan_id'))
				->withInput()
				->withErrors($testrun->errors());
		}
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
<?php

use \Theme;
use \Input;
use \DB;
use Nestor\Repositories\TestPlanRepository;
use Nestor\Repositories\TestRunRepository;
use Nestor\Repositories\NavigationTreeRepository;

class TestRunsController extends \NavigationTreeController {

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
		$test_plan_id = Input::get('test_plan_id');
		$testplan = $this->testplans->find($test_plan_id);
		$testruns = $this->testruns->findByTestPlanId($test_plan_id);
		$args = array();
		$args['testruns'] = $testruns;
		$args['testplan'] = $testplan;
		return $this->theme->scope('execution.testrun.index', $args)->render();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$test_plan_id = Input::get('test_plan_id');
		$args = array();
		$args['testplan'] = $this->testplans->find($test_plan_id);
		return $this->theme->scope('execution.create', $args)->render();
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
			return Redirect::to('/executions/')
				->with('flash', 'A new test run has been created');
		} else {
			return Redirect::to('/executions/create?test_plan_id=' . Input::get('test_plan_id'))
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
		$testrun = $this->testruns->find($id);
		$args['testrun'] = $testrun;
		$args['testplan'] = $testrun->testplan;
		return $this->theme->scope('execution.testrun.show', $args)->render();
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
		$testrun = $this->testruns->find($id);
		$args['testrun'] = $testrun;
		$args['testplan'] = $testrun->testplan;
		return $this->theme->scope('execution.testrun.edit', $args)->render();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		Log::info('Updating test run...');

		$testrun = $this->testruns->update(
				$id,
				Input::get('test_plan_id'),
				Input::get('name'),
				Input::get('description')
		);

		if ($testrun->isValid() && $testrun->isSaved())
		{
			return Redirect::route('execution.testruns.show', $id)
				->with('flash', 'The test run was updated');
		} else {
			return Redirect::route('execution.testruns.edit', $id)
				->withInput()
				->withErrors($testrun->errors());
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
		Log::info('Destroying test run...');
		$testrun = $this->testruns->find($id);
		$testplan = $testrun->testplan();
		$this->testruns->delete($id);

		return Redirect::to('execution/testruns?test_plan_id=' . $testplan->id)
			->with('flash', sprintf('The test run %s has been deleted', $testrun->name));
	}

}
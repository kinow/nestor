<?php

use \Theme;
use \Input;
use \DB;
use Nestor\Repositories\TestPlanRepository;
use Nestor\Repositories\TestCaseRepository;
use Nestor\Repositories\TestRunRepository;
use Nestor\Repositories\NavigationTreeRepository;
use Nestor\Repositories\ExecutionStatusRepository;
use Nestor\Repositories\ExecutionRepository;

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
	 * The test case repository implementation.
	 *
	 * @var Nestor\Repositories\TestCaseRepository
	 */
	protected $testcases;

	/**
	 * The execution status repository implementation.
	 *
	 * @var Nestor\Repositories\ExecutionStatusRepository
	 */
	protected $executionStatuses;

	/**
	 * The execution repository implementation.
	 *
	 * @var Nestor\Repositories\ExecutionRepository
	 */
	protected $executions;

	/**
	 * @var Nestor\Repositories\NavigationTreeRepository
	 */
	protected $nodes;

	protected $theme;

	public $restful = true;

	public function __construct(TestPlanRepository $testplans, 
		TestCaseRepository $testcases, 
		TestRunRepository $testruns, 
		NavigationTreeRepository $nodes, 
		ExecutionStatusRepository $executionStatuses,
		ExecutionRepository $executions)
	{
		parent::__construct();
		$this->testplans = $testplans;
		$this->testcases = $testcases;
		$this->testruns = $testruns;
		$this->nodes = $nodes;
		$this->executionStatuses = $executionStatuses;
		$this->executions = $executions;
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

	public function runGet($test_run_id) 
	{
		Log::info(sprintf('Executing Test Run %d', $test_run_id));
		$currentProject = $this->getCurrentProject();
		$testrun = $this->testruns->find($test_run_id);
		$testplan = $testrun->testplan;
		$testcases = $testplan->testcases;

		$showOnly = array(); // Our filter

		foreach ($testcases as $testcase)
		{
			$showOnly[$testcase->id] = TRUE;
		}

		$nodes = $this->nodes->children('1-'.$currentProject->id, 1 /* length*/);
		$navigationTree = $this->createNavigationTree($nodes, '1-'.$currentProject->id);
		$navigationTreeHtml = $this->createTestRunTreeHTML($navigationTree, $testrun->id, $showOnly);
		
		$args = array();
		$args['testrun'] = $testrun;
		$args['testplan'] = $testplan;
		$args['testcases'] = $testcases;
		$args['navigation_tree'] = $navigationTree;
		$args['navigation_tree_html'] = $navigationTreeHtml;
		$args['current_project'] = $this->currentProject;

		return $this->theme->scope('execution.testrun.run', $args)->render();
	}

	public function runTestCase($test_run_id, $test_case_id) 
	{
		Log::info(sprintf('Executing Test Run %d, Test Case %d', $test_run_id, $test_case_id));
		$currentProject = $this->getCurrentProject();
		$testrun = $this->testruns->find($test_run_id);
		$testplan = $testrun->testplan;
		$testcases = $testplan->testcases;
		$testcase = $this->testcases->find($test_case_id);
		$executionStatuses = $this->executionStatuses->all();

		$showOnly = array(); // Our filter

		foreach ($testcases as $testcase)
		{
			$showOnly[$testcase->id] = TRUE;
		}

		$nodes = $this->nodes->children('1-'.$currentProject->id, 1 /* length*/);
		$navigationTree = $this->createNavigationTree($nodes, '1-'.$currentProject->id);
		$navigationTreeHtml = $this->createTestRunTreeHTML($navigationTree, $testrun->id, $showOnly, $test_case_id);
		
		$args = array();
		$args['testrun'] = $testrun;
		$args['testplan'] = $testplan;
		$args['testcases'] = $testcases;
		$args['testcase'] = $testcase;
		$args['executionStatuses'] = $executionStatuses;
		$args['navigation_tree'] = $navigationTree;
		$args['navigation_tree_html'] = $navigationTreeHtml;
		$args['current_project'] = $this->currentProject;

		return $this->theme->scope('execution.testrun.runTestcase', $args)->render();
	}

	public function runTestCasePost($test_run_id, $test_case_id) 
	{
		echo "EAE! " . $test_run_id . " - " . $test_case_id;
		$testrun = $this->testruns->find($test_run_id);
		$testcase = $this->testcases->find($test_case_id);
		$execution = $this->executions->create($testrun->id, 
			$testcase->id, 
			Input::get('execution_status_id'), 
			Input::get('notes'));

		if ($execution->isValid() && $execution->isSaved())
		{
			return Redirect::to(Request::url())->with('flash', 'A new test run has been created');
		} else {
			return Redirect::to(Request::url())
				->withInput()
				->withErrors($execution->errors());
		}
	}

}
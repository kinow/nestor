<?php

use Nestor\Model\Nodes;
use Nestor\Model\ExecutionStatus;
use Nestor\Util\NavigationTreeUtil;
use Nestor\Util\JUnitProducer;

use utilphp\util;

class TestRunsController extends NavigationTreeController 
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
		$testPlanId = Input::get('test_plan_id');
		$testPlan = HMVC::get("api/v1/testplans/$testPlanId");
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Execution', URL::to('/execution'))->
			add(sprintf('Test Runs for Test Plan %s', $testPlan['name']));
		$testRuns = HMVC::get("api/v1/testplans/$testPlanId/testruns");
		$args = array();
		$args['testruns'] = $testRuns;
		$args['testplan'] = $testPlan;
		return $this->theme->scope('execution.testrun.index', $args)->render();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$testPlanId = Input::get('test_plan_id');
		$testPlan = HMVC::get("api/v1/testplans/$testPlanId");
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Execution', URL::to('/execution'))->
			add(sprintf('Create Test Run for Test Plan %s', $testPlan['name']));
		$args = array();
		$args['testplan'] = $testPlan;
		return $this->theme->scope('execution.create', $args)->render();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
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
		$testrun = HMVC::get("api/v1/execution/testruns/$id");
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Execution', URL::to('/execution'))->
			add(sprintf('Test Run %s', $testrun['name']));
		$args['testrun'] = $testrun;
		$args['testplan'] = $testrun['test_plan'];
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
		$testrun = HMVC::get("api/v1/execution/testruns/$id");
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Execution', URL::to('/execution'))->
			add(sprintf('Test Run %s', $testrun['name']));
		$args['testrun'] = $testrun;
		$args['testplan'] = $testrun['testplan'];
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
		$testRun = HMVC::put("api/v1/executions/$id", Input::all());

		if (!$testRun || (isset($testRun['code']) && $testRun['code'] != 200)) {
			return Redirect::to(URL::previous())->withInput()->withErrors($testRun['description']);
		}

		return Redirect::to("/execution/testruns/$id")
			->with('success', sprintf('Test Run %s updated', $testRun['name']));
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
		$testplan = $testrun->testplan()->first();
		$this->testruns->delete($id);

		return Redirect::to('execution/testruns?test_plan_id=' . $testplan->id)
			->with('success', sprintf('The test run %s has been deleted', $testrun->name));
	}

	public function runGet($testRunId) 
	{
		Log::info(sprintf('Executing Test Run %d', $testRunId));
		$currentProject = $this->getCurrentProject();
		$testRun = HMVC::get("api/v1/execution/testruns/$testRunId");
		$testPlanId = $testRun['test_plan_id'];
		$testPlan = HMVC::get("api/v1/testplans/$testPlanId");
		$testCaseVersions = $testPlan['test_cases'];

		Log::debug('Creating breadcrumb');
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Execution', URL::to('/execution'))->
			add(sprintf('Test Runs for Test Plan %s', $testPlan['name']), URL::to(sprintf('/execution/testruns?test_plan_id=%d', $testPlan['id'])))->
			add(sprintf('Test Run %s', $testRun['name']));

		$filter = array(); // Our filter
		foreach ($testCaseVersions as $version)
		{
			$filter[$version['test_case_id']] = TRUE;
		}

		$nodeId = Nodes::id(Nodes::PROJECT_TYPE, $currentProject['id']);
		$nodes = HMVC::get("api/v1/nodes/$nodeId");

		// create a navigation tree
		$navigationTree = NavigationTreeUtil::createNavigationTree(
			$nodes, Nodes::id(Nodes::PROJECT_TYPE, $currentProject['id'])
		);

		// use it to create the HTML version
		$navigationTreeHtml = NavigationTreeUtil::createExecutionNavigationTreeHtml(
			$navigationTree, 
			NULL, 
			$this->theme->getThemeName(),
			array(), 
			$filter,
			$testRunId
		);

		$args = array();
		$args['testrun'] = $testRun;
		$args['testplan'] = $testPlan;
		$args['testcases'] = $testCaseVersions;
		$args['navigation_tree'] = $navigationTree;
		$args['navigation_tree_html'] = $navigationTreeHtml;
		$args['current_project'] = $this->currentProject;

		return $this->theme->scope('execution.testrun.run', $args)->render();
	}

	public function runTestCase($testRunId, $testCaseId) 
	{
		Log::info(sprintf('Executing Test Run %d, Test Case %d', $testRunId, $testCaseId));
		$currentProject = $this->getCurrentProject();
		$testRun = HMVC::get("api/v1/execution/testruns/$testRunId");
		$testPlanId = $testRun['test_plan_id'];
		$testPlan = HMVC::get("api/v1/testplans/$testPlanId");
		$testCaseVersions = $testPlan['test_cases'];
		$testCase = HMVC::get("api/v1/testcases/$testCaseId");
		$testCaseVersion = $testCase['version'];

		$executionStatuses = HMVC::get("api/v1/executionstatuses/");

		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Execution', URL::to('/execution'))->
			add(sprintf('Test Runs for Test Plan %s', $testPlan['name']), 
				URL::to(sprintf('/execution/testruns?test_plan_id=%d', $testPlan['id'])))->
			add(sprintf('Test Run %s', $testRun['name']));

		$showOnly = array(); // Our filter

		$assignee = null;
		foreach ($testCaseVersions as $testCaseVersion2)
		{
			$showOnly[$testCaseVersion2['test_case_id']] = $testCaseVersion2;
			if ($testCaseVersion2['id'] == $testCaseVersion['id'])
			{
				$assigneeId = isset($testCaseVersion2['assignee']) ?: null;
				if (is_null($assigneeId))
				{
					$assignee = "Not assigned";
				}
				else
				{
					$user = $this->users->find($assigneeId);
					$assignee = $user->fullname;
				}
			}
		}

		$filter = array(); // Our filter
		foreach ($testCaseVersions as $version)
		{
			$filter[$version['test_case_id']] = TRUE;
		}

		$nodeId = Nodes::id(Nodes::PROJECT_TYPE, $currentProject['id']);
		$nodes = HMVC::get("api/v1/nodes/$nodeId");

		// create a navigation tree
		$navigationTree = NavigationTreeUtil::createNavigationTree(
			$nodes, Nodes::id(Nodes::PROJECT_TYPE, $currentProject['id'])
		);

		// use it to create the HTML version
		$navigationTreeHtml = NavigationTreeUtil::createExecutionNavigationTreeHtml(
			$navigationTree, 
			Nodes::id(Nodes::TEST_CASE_TYPE, $testCaseId), 
			$this->theme->getThemeName(),
			array(), 
			$filter,
			$testRunId
		);

		$testCaseVersionId = $testCaseVersion['id'];
		$executions = HMVC::get("api/v1/execution/testruns/$testRunId/executions/$testCaseVersionId");

		$lastExecution = end($executions);
		$lastExecutionStatusId = ExecutionStatus::NOT_RUN; 
		if ($lastExecution)
		{
			$lastExecutionStatusId = $lastExecution['execution_status_id'];
		}

		$steps = $testCase['version']['steps'];
		foreach ($steps as $step)
		{
			if ($lastExecutionStatusId > ExecutionStatus::NOT_RUN)
			{
				$stepLastExecution = $this->stepExecutions->findByStepIdAndExecutionId($step->id, $lastExecution->id)->first();
				if ($stepLastExecution)
					$step->lastExecutionStatusId = $stepLastExecution->execution_status_id;
				else
					$step->lastExecutionStatusId = 1;
			}
			else
			{
				$step['lastExecutionStatusId'] = ExecutionStatus::NOT_RUN; 
			}
		}

		$args = array();
		$args['testrun'] = $testRun;
		$args['testplan'] = $testPlan;
		$args['testcases'] = $testCaseVersions;
		$args['testcase'] = $testCase;
		$args['testcaseVersion'] = $testCaseVersion;
		$args['assignee'] = $assignee;
		$args['steps'] = $steps;
		$args['executions'] = $executions;
		$args['executionStatuses'] = $executionStatuses;
		$args['last_execution_status_id'] = $lastExecutionStatusId;
		$args['navigation_tree'] = $navigationTree;
		$args['navigation_tree_html'] = $navigationTreeHtml;
		$args['current_project'] = $this->currentProject;

		return $this->theme->scope('execution.testrun.runTestcase', $args)->render();
	}

	public function runTestCasePost($testRunId, $testCaseId) 
	{
		$testCaseId = Input::get('test_case_id');
		$execution = HMVC::post('api/v1/execution/testruns/$testRunId/executions/$testCaseId', Input::all());
		if (!$execution || (isset($execution['code']) && $execution['code'] != 200)) {
			return Redirect::to(URL::previous())->withInput()->withErrors($execution['description']);
		}

		return Redirect::to(Request::url())->with('success', 'Test executed successfully!');
	}

	public function getJUnit($testRunId)
	{
		Log::info(sprintf('Retrieving JUnit report for Test Run %d', $testRunId));
		$currentProject = $this->getCurrentProject();
		$testRun = HMVC::get("api/v1/execution/testruns/$testRunId");
		$testPlan = $testRun['test_plan'];

		$executionStatuses = HMVC::get("api/v1/executionstatuses/");

        // TODO's:
		// get test suites
		// create right array

		$testSuites = HMVC::get("api/v1/execution/testruns/$testRunId/testsuites");
		dd($testSuites);exit;
		$testcases = $this->testruns->getTestCases($testRunId);

		$ts = array();
		foreach ($testsuites as $testsuite)
		{
			$tcs = array();
			foreach ($testcases as $testcase)
			{
				if ($testcase->test_suite_id == $testsuite->id)
				{
					$testcaseObj = $testcase;
					$tcs[$testcase->id] = $testcaseObj;
				}
			}
			$testsuiteObj = (object) $testsuite->toArray(); // detach
			$testsuiteObj->testcases = $tcs;
			$ts[$testsuite->id] = $testsuiteObj;
		}

		$producer = new JUnitProducer();

		$document = $producer->produce($ts);
		// Create doc and put in args

		$download = Input::get('download');
		if (isset($download) && $download == 'true')
		{
			return Response::make($document->saveXML(), '200', array(
			    'Content-Type' => 'application/octet-stream',
			    'Content-Disposition' => 'attachment; filename="junit.xml"'
			));
		}

		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Execution', URL::to('/execution'))->
			add(sprintf('Test Runs for Test Plan %s', $testplan->name), URL::to(sprintf('/execution/testruns?test_plan_id=%d', $testplan->id)))->
			add(sprintf('Test Run %s', $testrun->name), URL::to('/execution/testruns/' . $testRunId));

		$args = array();
		$args['testrun'] = $testRun;
		$args['testplan'] = $testPlan;
		$args['document'] = $document->saveXML();
		$args['current_project'] = $currentProject;
		$args['execution_statuses'] = $executionStatuses;

		return $this->theme->scope('execution.testrun.junit', $args)->render();
	}

}
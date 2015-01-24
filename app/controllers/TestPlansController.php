<?php

use Nestor\Repositories\TestPlanRepository;
use Nestor\Repositories\TestCaseRepository;
use Nestor\Repositories\NavigationTreeRepository;
use Nestor\Repositories\UserRepository;

class TestPlansController extends BaseController {


	protected $theme;

	public $restful = true;

	public function __construct()
	{
		parent::__construct();
		$this->theme->setActive('planning');
	}

	public function index()
	{
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Planning');
		$args = array();
		$projectId = $this->getCurrentProjectId();
		$testPlans = HMVC::get("api/v1/projects/$projectId/testplans/", Input::all());
		$args['testplans'] = $testPlans;
		return $this->theme->scope('testplan.index', $args)->render();
	}

	public function create()
	{
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Planning', URL::to('/planning'))->
			add('Create new test plan');
		$args = array();
		$args['project'] = $this->getCurrentProject();
		return $this->theme->scope('testplan.create', $args)->render();
	}

	public function store()
	{
		$testPlan = HMVC::post('api/v1/testplans/', Input::all());

		if (!$testPlan || (isset($testPlan['code']) && $testPlan['code'] != 200)) {
			return Redirect::to(URL::previous())->withInput()->withErrors($testPlan['description']);
		}

		return Redirect::to('/testplans/')
			->with('success', sprintf('Test Plan %s created', $testPlan['name']));
	}

	public function show($id)
	{
		$testplan = $this->testplans->find($id);
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Planning', URL::to('/planning'))->
			add(sprintf('Test plan %s', $testplan->name));
		$users = $this->users->all();
		$args = array();
		$args['testplan'] = $testplan;
		$args['testcases'] = $testplan->testcaseVersions()->get();
		$args['users'] = $users;
		return $this->theme->scope('testplan.show', $args)->render();
	}

	public function edit($id)
	{
		$testplan = $this->testplans->find($id);
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Planning', URL::to('/planning'))->
			add(sprintf('Test plan %s', $testplan->name));
		$args = array();
		$args['testplan'] = $testplan;
		$args['project'] = $this->getCurrentProject();
		return $this->theme->scope('testplan.edit', $args)->render();
	}

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

	public function destroy($id)
	{
		Log::info('Destroying test plan...');
		$testplan = $this->testplans->find($id);
		$this->testplans->delete($id);

		return Redirect::route('testplans.index')
			->with('success', sprintf('The test plan %s has been deleted', $testplan->name));
	}

	public function addTestCases($id)
	{
		// This is the current test plan
		$testplan = $this->testplans->find($id);
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Planning', URL::to('/planning'))->
			add(sprintf('Test plan %s', $testplan->name), URL::to(sprintf('/planning/%s', $testplan->id)))->
			add('Add Test Cases');
		$currentProject = $this->getCurrentProject();
		$nodesSelected = array();
		$testcases = $testplan->testcases();

		foreach ($testcases as $testcase)
		{
			$nodesSelected[$testcase->id] = TRUE;
		}

		$nodes = $this->nodes->children('1-'.$currentProject->id, 1 /* length*/);
		$navigationTree = $this->createNavigationTree($nodes, '1-'.$currentProject->id);
		$navigationTreeHtml = $this->createTestPlanTreeHTML($navigationTree, "2-" . $testplan->id, $this->theme->getThemeName(), $nodesSelected);
		
		$args = array();
		$args['testplan'] = $testplan;
		$args['nodesSelected'] = $nodesSelected;
		$args['navigation_tree'] = $navigationTree;
		$args['navigation_tree_html'] = $navigationTreeHtml;
		$args['current_project'] = $this->currentProject;
		return $this->theme->scope('testplan.addTestCases', $args)->render();
	}

	public function storeTestCases($id)
	{
		$testplan = $this->testplans->find($id);
		$existingTestcaseVersions = $testplan->testcaseVersions()->get();
		$length = count($_POST);
		$nodesSelected = array();
		$testcases = array();
		foreach ($_POST as $entry => $value)
		{
			if (strpos($entry, 'ft_') === 0 && strpos($entry, 'ft_1_active') !== 0)
			{
				if (is_array($value))
				{
					foreach ($value as $tempValue)
						$nodesSelected[] = $tempValue;
				}
				else 
				{
					$nodesSelected[] = $value;
				}
			}
		}
		foreach ($nodesSelected as $node)
		{
			$children = $this->nodes->children($node);
			$this->getTestCasesFrom($children, $testcases);
		}
		// What to remove?
		$testcasesForRemoval = array();
		foreach ($existingTestcaseVersions as $existing)
		{
			$found = FALSE;
			foreach ($testcases as $testcase)
			{
				if ($existing->test_case_id == $testcase->id) 
				{
					$found = TRUE;
				}
			}
			if (!$found)
			{
				$testcasesForRemoval[] = $existing;
			}
		}
		// What do add?
		$testcasesForAdding = array();
		foreach ($testcases as $testcase)
		{
			$found = FALSE;
			foreach ($existingTestcaseVersions as $existing)
			{
				if ($existing->test_case_id == $testcase->id) 
				{
					$found = TRUE;
				}
			}
			if (!$found)
			{
				$testcasesForAdding[] = $testcase->latestVersion();
			}
		}

		foreach ($testcasesForAdding as $addMe)
		{
			Log::info(sprintf('Adding testcase %s version %s to test plan %s', $addMe->name, $addMe->version, $testplan->name));
			$testplan->testcaseVersions()->attach($addMe);
		}

		foreach ($testcasesForRemoval as $removeMe)
		{
			Log::info(sprintf('Removing test case %s version %s from test plan %s', $removeMe->name, $removeMe->version, $testplan->name));
			$testplan->testcaseVersions()->detach($removeMe);
		}

		return Redirect::to('/planning/' . $id)
				->with('success', sprintf('%d test cases added, and %d removed', count($testcasesForAdding), count($testcasesForRemoval)));
	}

	protected function getTestCasesFrom($children, &$testcases)
	{

		foreach ($children as $child)
		{
			$executionType = $child->getDescendantExecutionType();
			if ($executionType == 3)
			{
				$nodeId = $child->getDescendantNodeId();
				$testcases[$nodeId] = $this->testcases->find($nodeId);
			}
			if (isset($child->children) && !empty($child->children))
			{
				$this->getTestCasesFrom($children, $testcases);
			}
		}
	}

	public function postAssingTestCases($testPlanId)
	{
		$testcases = Input::get('testcases');
		$users = Input::get('users');

		for ($i = 0; $i < count($testcases); ++$i)
		{
			$testcaseVersionId = $testcases[$i];
			$userId = $users[$i];

			if ($userId == 0)
			{
				$userId = NULL;
			}
			$this->testplans->assign($testPlanId, $testcaseVersionId, $userId);
		}

		return Redirect::to('/planning/' . $testPlanId)
			->with('success', 'Tests assigned!');
	}

}
<?php

use \Theme;
use \Input;
use \DB;
use Nestor\Repositories\TestPlanRepository;
use Nestor\Repositories\TestCaseRepository;
use Nestor\Repositories\NavigationTreeRepository;

class TestPlansController extends \NavigationTreeController {

	/**
	 * The test plan repository implementation.
	 *
	 * @var Nestor\Repositories\TestPlanRepository
	 */
	protected $testplans;

	/**
	 * The test case repository implementation.
	 *
	 * @var Nestor\Repositories\TestCaseRepository
	 */
	protected $testcases;

	/**
	 * @var Nestor\Repositories\NavigationTreeRepository
	 */
	protected $nodes;

	protected $theme;

	public $restful = true;

	public function __construct(TestPlanRepository $testplans, TestCaseRepository $testcases, NavigationTreeRepository $nodes)
	{
		parent::__construct();
		$this->testplans = $testplans;
		$this->testcases = $testcases;
		$this->nodes = $nodes;
		$this->theme->setActive('planning');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$args = array();
		$args['testplans'] = $this->testplans->all();
		return $this->theme->scope('testplan.index', $args)->render();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$args = array();
		$args['project'] = $this->getCurrentProject();
		return $this->theme->scope('testplan.create', $args)->render();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		Log::info('Creating test plan...');

		$testplan = $this->testplans->create(
				Input::get('project_id'),
				Input::get('name'),
				Input::get('description')
		);

		if ($testplan->isValid() && $testplan->isSaved())
		{
			return Redirect::to('/planning/')
				->with('flash', 'A new test plan has been created');
		} else {
			return Redirect::to('/planning/create')
				->withInput()
				->withErrors($testplan->errors());
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
				->with('flash', 'The test plan was updated');
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
			->with('flash', sprintf('The test plan %s has been deleted', $testplan->name));
	}

	public function addTestCases($id)
	{
		$currentProject = $this->getCurrentProject();
		$nodes = $this->nodes->children('1-'.$currentProject->id, 1 /* length*/);
		$navigationTree = $this->createNavigationTree($nodes, '1-'.$currentProject->id);
		$navigationTreeHtml = $this->createTestPlanTreeHTML($navigationTree, "", $this->theme->getThemeName());
		$testplan = $this->testplans->find($id);
		$args = array();
		$args['testplan'] = $testplan;
		$args['navigation_tree'] = $navigationTree;
		$args['navigation_tree_html'] = $navigationTreeHtml;
		$args['current_project'] = $this->currentProject;
		return $this->theme->scope('testplan.addTestCases', $args)->render();
	}

	function startsWith($haystack, $needle)
	{
		return $needle === "" || strpos($haystack, $needle) === 0;
	}

	public function storeTestCases($id)
	{
		$testplan = $this->testplans->find($id);
		$length = count($_POST);
		$nodesSelected = array();
		$testcases = array();
		foreach ($_POST as $entry => $value)
		{
			if (strpos($entry, 'ft_1') === 0)
			{
				$nodesSelected[] = $value;
			}
		}
		foreach ($nodesSelected as $node)
		{
			$children = $this->nodes->children($node);
			$this->getTestCasesFrom($children, $testcases);
		}
// 		var_dump($testcases);
// 		echo "Add these test cases to test plan #" . $id;exit;

		// TODO: attach entities to test_plans_test_cases/test_plans

		foreach ($testcases as $testcase)
		{
			Log::info(sprintf('Adding testcase %s to test plan %s', $testcase->name, $testplan->name));
			$testplan->testcases()->attach($testcase);
		}

		return Redirect::to('/planning/' . $id)
				->with('success', sprintf('%d test cases were added to the test plan %s', count($testcases), $testplan->name));
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

}
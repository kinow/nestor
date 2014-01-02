<?php

use \Theme;
use \Input;
use \DB;
use Nestor\Repositories\TestPlanRepository;
use Nestor\Repositories\NavigationTreeRepository;

class TestPlansController extends \NavigationTreeController {

	/**
	 * The test plan repository implementation.
	 *
	 * @var Nestor\Repositories\TestPlanRepository
	 */
	protected $testplans;
	
	/**
	 * @var Nestor\Repositories\NavigationTreeRepository
	 */
	protected $nodes;

	protected $theme;

	public $restful = true;

	public function __construct(TestPlanRepository $testplans, NavigationTreeRepository $nodes)
	{
		parent::__construct();
		$this->testplans = $testplans;
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
		$args['testplan'] = $this->testplans->find($id);
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
	
	public function storeTestCases($id)
	{
		echo "WIP... id: " . $id;
		exit;
	}

}
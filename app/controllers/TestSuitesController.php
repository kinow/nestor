<?php

use Nestor\Model\Nodes;

class TestSuitesController extends NavigationTreeController {

	protected $theme;
	public $restful = true;

	public function __construct()
	{
		parent::__construct();
		$this->theme->setActive('testsuites');
	}

	public function index()
	{
		return Redirect::to('/specification');
	}

	public function create()
	{
		return Redirect::to('/specification');
	}

	public function store()
	{
		$testSuite = HMVC::post('api/v1/testsuites/', Input::all());

		if (!$testSuite) {
			Session::flash('error', 'Failed to create Test Suite');
			return Redirect::to(URL::previous())->withInput();
		} else if (isset($testSuite['code']) && $testSuite['code'] != 200) {
			return Redirect::to(URL::previous())->withInput()->withErrors($testSuite['description']);
		}

		return Redirect::to(sprintf('/specification/nodes/%s', Nodes::id(Nodes::TEST_SUITE_TYPE, $testSuite['id'])))
			->with('success', sprintf('New test suite %s created', $testSuite['name']));
	}

	public function show($id)
	{
		$testSuite = HMVC::get("api/v1/testsuites/$id", Input::all());
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Specification', URL::to('/specification'))->
			add(sprintf('Test Suite %s', $testSuite['name']));
		$args = array();
		$args['testsuite'] = $testSuite;
		return $this->theme->scope('testsuite.show', $args)->render();
	}

	public function edit($id)
	{
		$testSuite = HMVC::get("api/v1/testsuites/$id", Input::all());
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Specification', URL::to('/specification'))->
			add(sprintf('Test Suite %s', $testSuite['name']));
		$args = array();
		$args['testsuite'] = $testSuite;
		return $this->theme->scope('testsuite.edit', $args)->render();
	}

	public function update($id)
	{
		$testSuite = HMVC::put("api/v1/testsuites/$id", Input::all());

		if (!$testSuite || (isset($testSuite['code']) && $testSuite['code'] != 200)) {
			return Redirect::to(URL::previous())->withInput()->withErrors($testSuite['description']);
		}

		return Redirect::to('specification/nodes/' . Nodes::id(Nodes::TEST_SUITE_TYPE, $id))
			->with('success', sprintf('The test suite %s was updated', $testSuite['name']));
	}

	public function destroy($id)
	{
		$testSuite = HMVC::delete("api/v1/testsuites/$id", Input::all());

		if (!$testSuite || (isset($testSuite['code']) && $testSuite['code'] != 200)) {
			return Redirect::to(URL::previous())->withInput()->withErrors($testSuite['description']);
		}

		return Redirect::to('/specification')
			->with('success', sprintf('The test suite %s has been deleted', $testSuite['name']));
	}

	public function postCopy()
	{
		// parameters from the screen
		$from = Input::get('copy_name');
		$to = Input::get('copy_new_name');
		$ancestor = Input::get('ancestor');

		$currentProject = $this->getCurrentProject();

		Log::info(sprintf('Copying test suite %s into %s', $from, $to));

		$pdo = null;
		try {
			// DB transaction
			$pdo = DB::connection()->getPdo();
			$pdo->beginTransaction();
			// copy root node 
			list($old, $testsuite) = $this->testsuites->copy($from, $to, $ancestor, $currentProject->id, $this->nodes, $this->testcases, $this->testcaseSteps);
			
			Log::info(sprintf('Test suite %s copied successfully into %s', $from, $to));
			$pdo->commit();
		} catch (\Exception $e) {
			Log::error("Error copying test suite: " . $e->getMessage());
			if (!is_null($pdo))
				$pdo->rollBack();
			$messages = new Illuminate\Support\MessageBag;
			$messages->add('nestor.customError', $e->getMessage());
			return Redirect::to('/specification/nodes/1-'.$currentProject->id)
				->withInput()
				->withErrors($messages);
		}

		return Redirect::to('/specification/nodes/2-' . $testsuite->id)
			->with('success', sprintf('The test suite %s has been copied into %s', $from, $to));
	}

}
<?php

use Nestor\Model\ExecutionStatus;
use Nestor\Model\Nodes;

class TestCasesController extends BaseController {

	protected $theme;
	public $restful = true;

	public function __construct()
	{
		parent::__construct();
		$this->theme->setActive('testcases');
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
		$testCase = HMVC::post('api/v1/testcases/', Input::all());
		
		if (!$testCase) {
			Session::flash('error', 'Failed to create Test Case');
			return Redirect::to(URL::previous())->withInput();
		} else if(isset($testCase['code']) && $testCase['code'] != 200) {
			return Redirect::to(URL::previous())->withInput()->withErrors($testCase['description']);
		}

		return Redirect::to(sprintf('/specification/nodes/%s', Nodes::id(Nodes::TEST_CASE_TYPE, $testCase['id'])))
			->with('success', sprintf('New test case %s created', $testCase['version']['name']));
	}

	public function show($id)
	{
		return Redirect::to(sprintf('/specification/nodes/%s', Nodes::id(Nodes::TEST_CASE_TYPE, $id)));
	}

	public function edit($id)
	{
		$testCase = HMVC::get("api/v1/testcases/$id");
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Specification', URL::to('/specification'))->
			add(sprintf('Test Case %s', $testCase['version']['name']));
		$args = array();
		$args['testcase'] = $testCase;
		$executionTypes = HMVC::get("api/v1/executiontypes/");
		$args['execution_types'] = $executionTypes;

		$executionTypesIds = array();
		foreach ($executionTypes as $executionType)
		{
			$executionTypesIds[$executionType['id']] = $executionType['name'];
		}
		$args['execution_type_ids'] = $executionTypesIds;

		$executionStatuses = HMVC::get("api/v1/executionstatuses/");
		foreach ($executionStatuses as $key => $executionStatus) {
			if ($executionStatus['id'] == ExecutionStatus::NOT_RUN) {
				unset($executionStatuses[$key]);
			}
		}
		$executionStatuses = array_values($executionStatuses);
		$args['execution_statuses'] = json_encode($executionStatuses);

		return $this->theme->scope('testcase.edit', $args)->render();
	}

	public function update($id)
	{
		$testCase = HMVC::put("api/v1/testcases/$id", Input::all());

		if (!$testCase || (isset($testCase['code']) && $testCase['code'] != 200)) {
			return Redirect::to(URL::previous())->withInput()->withErrors($testCase['description']);
		}

		return Redirect::to(sprintf('/specification/nodes/%s', Nodes::id(Nodes::TEST_CASE_TYPE, $testCase['id'])))
			->with('success', sprintf('The test case %s was updated', $testCase['version']['name']));
	}

	public function destroy($id)
	{
		Log::info(sprintf('Deleting test case %d...', $id));
		$parent = null;
		$pdo = null;
		try {
			$pdo = DB::connection()->getPdo();
			$pdo->beginTransaction();
			$parent = $this->nodes->parent('3-'.$id);
			Log::info(sprintf('The parent node of the deleted is %s', $parent->ancestor));
			$testcasesDeleted = $this->testcases->delete($id);
			$nodesDeleted = $this->nodes->delete('3-'.$id);

			if ($testcasesDeleted !== 1)
			{
				throw new Exception('Failed to delete test case');
			}

			// if ($nodesDeleted !== 1)
			// {
			// 	$queries = DB::getQueryLog();
			// 	$last_query = end($queries);
			// 	Log::info($last_query);
			// 	throw new Exception('Failed to delete node');
			// }

			$pdo->commit();

			return Redirect::to('/specification/nodes/' . $parent->ancestor);
		} catch (\Exception $e) {
			Log::error($e);
			if (!is_null($pdo))
				$pdo->rollBack();
			return Redirect::to('/testcases/' . $id)->withInput();
		}
	}

}
<?php

use \Theme;
use \Input;
use \DB;
use Nestor\Repositories\TestCaseRepository;
use Nestor\Repositories\ExecutionTypeRepository;
use Nestor\Repositories\NavigationTreeRepository;

class TestCasesController extends \BaseController {

	/**
	 * The test case repository implementation.
	 *
	 * @var Nestor\Repositories\TestCaseRepository
	 */
	protected $testcases;

	/**
	 * The execution type repository implementation.
	 *
	 * @var Nestor\Repositories\ExecutionTypeRepository
	 */
	protected $executionTypes;

	/**
	 * The navigation tree node repository implementation.
	 *
	 * @var Nestor\Repositories\NavigationTreeRepository
	 */
	protected $nodes;

	protected $theme;

	public $restful = true;

	public function __construct(TestCaseRepository $testcases, ExecutionTypeRepository $executionTypes, NavigationTreeRepository $nodes)
	{
		parent::__construct();
		$this->testcases = $testcases;
		$this->executionTypes = $executionTypes;
		$this->nodes = $nodes;
		$this->theme->setActive('testcases');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$testcase = null;
		$navigationTreeNode = null;
		Log::info('Creating test case...');
		$pdo = null;
		try {
			$pdo = DB::connection()->getPdo();
    		$pdo->beginTransaction();
			$testcase = $this->testcases->create(
					Input::get('project_id'),
					Input::get('test_suite_id'),
					Input::get('execution_type_id'),
					Input::get('name'),
					Input::get('description')
			);
			$ancestor = Input::get('ancestor');
			if ($testcase->isValid() && $testcase->isSaved())
			{
				$navigationTreeNode = $this->nodes->create(
						$ancestor,
						'3-' . $pdo->lastInsertId(),
						$pdo->lastInsertId(),
						3,
						$testcase->name
				);
				if ($navigationTreeNode)
				{
					$pdo->commit();
				}
			}
		} catch (\PDOException $e) {
			if (!is_null($pdo))
				$pdo->rollBack();
			return Redirect::to('/specification/')
	 			->withInput();
		}
		if ($testcase->isSaved() && $navigationTreeNode)
		{
			return Redirect::to('/specification/nodes/' . '3-' . $testcase->id)
				->with('flash', 'A new test case has been created');
		} else {
			return Redirect::to('/specification/')
				->withInput()
				->withErrors($testcase->errors());
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
		//
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
		$args['testcase'] = $this->testcases->find($id);
		$args['execution_types'] = $this->executionTypes->all();
		$execution_types_ids = array();
		foreach ($args['execution_types'] as $execution_type)
		{
			$execution_types_ids[$execution_type->id] = $execution_type->name;
		}
		$args['execution_type_ids'] = $execution_types_ids;
		return $this->theme->scope('testcase.edit', $args)->render();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$testcase = null;
		$navigationTreeNode = null;
		Log::info('Updating test case...');
		$pdo = null;
		try {
			$pdo = DB::connection()->getPdo();
			$pdo->beginTransaction();
			$testcase = $this->testcases->update($id,
							Input::get('project_id'),
							Input::get('test_suite_id'),
							Input::get('execution_type_id'),
							Input::get('name'),
							Input::get('description'));
			if (!$testcase->isValid() || !$testcase->isSaved())
			{
				throw new Exception('Failed to update Test Case');
			}
			$navigationTreeNode = $this->nodes->find('3-'.$testcase->id, '3-'.$testcase->id);
			$navigationTreeNode->display_name = $testcase->name;
			$updatedNode = $this->nodes->update(
						'3-'.$testcase->id,
						'3-'.$testcase->id,
						$navigationTreeNode->node_id,
						$navigationTreeNode->node_type_id,
						$navigationTreeNode->display_name);
			if (!$updatedNode->isValid() || !$updatedNode->isSaved())
			{
				throw new Exception('Failed to update Node');
			}
			$pdo->commit();	
		} catch (\Exception $e) {
			if (!is_null($pdo))
				$pdo->rollBack();
			return Redirect::to('/testcases/' . $id)->withInput();
		}
		if (!is_null($testcase) && $testcase->isSaved())
		{
			return Redirect::to(sprintf('/specification/nodes/%s-%s', $navigationTreeNode->node_type_id, $navigationTreeNode->node_id))
				->with('message', 'A new test case has been created');
		} else {
			return Redirect::to('/testcases/' . $id)
				->withInput()
				->withErrors($testcase->errors());
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
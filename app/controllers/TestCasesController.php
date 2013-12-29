<?php

use Theme;
use Input;
use DB;
use Nestor\Repositories\TestCaseRepository;
use Nestor\Repositories\NavigationTreeRepository;

class TestCasesController extends \BaseController {

	/**
	 * The test case repository implementation.
	 *
	 * @var Nestor\Repositories\TestCaseRepository
	 */
	protected $testcases;

	/**
	 * The navigation tree node repository implementation.
	 *
	 * @var Nestor\Repositories\NavigationTreeRepository
	 */
	protected $nodes;

	protected $theme;

	public $restful = true;

	public function __construct(TestCaseRepository $testcases, NavigationTreeRepository $nodes)
	{
		parent::__construct();
		$this->testcases = $testcases;
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
			$parent_id = Input::get('parent_id');
			if (!$parent_id)
			{
				$parent_id = $testcase->test_suite_id;
			}
			if ($testcase->isValid() && $testcase->isSaved())
			{
				$navigationTreeNode = $this->nodes->create(
						$pdo->lastInsertId(),
						3,
						$parent_id,
						$testcase->name
				);
				if ($navigationTreeNode->isValid() && $navigationTreeNode->isSaved())
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
		if ($testcase->isSaved() && $navigationTreeNode->isSaved())
		{
			return Redirect::to('/specification/nodes/' . $navigationTreeNode->parent_id)
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
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
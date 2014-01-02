<?php

use \Theme;
use \Input;
use \DB;
use Nestor\Repositories\TestSuiteRepository;
use Nestor\Repositories\NavigationTreeRepository;

class TestSuitesController extends \BaseController {

	/**
	 * The test suite repository implementation.
	 *
	 * @var Nestor\Repositories\TestSuiteRepository
	 */
	protected $testsuites;

	/**
	 * The navigation tree node repository implementation.
	 *
	 * @var Nestor\Repositories\NavigationTreeRepository
	 */
	protected $nodes;

	protected $theme;

	public $restful = true;

	public function __construct(TestSuiteRepository $testsuites, NavigationTreeRepository $nodes)
	{
		parent::__construct();
		$this->testsuites = $testsuites;
		$this->nodes = $nodes;
		$this->theme->setActive('testsuites');
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
		$testsuite = null;
		$navigationTreeNode = null;
		Log::info('Creating test suite...');
		$pdo = null;
		try {
			$pdo = DB::connection()->getPdo();
    		$pdo->beginTransaction();
			$testsuite = $this->testsuites->create(
					Input::get('project_id'),
					Input::get('name'),
					Input::get('description')
			);
			$ancestor = Input::get('ancestor');
			if ($testsuite->isValid() && $testsuite->isSaved())
			{
				$navigationTreeNode = $this->nodes->create(
						$ancestor,
						'2-' . $pdo->lastInsertId(),
						$pdo->lastInsertId(),
						2,
						$testsuite->name
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
		if ($testsuite->isSaved() && $navigationTreeNode)
		{
			return Redirect::to('/specification/nodes/' . '2-' . $testsuite->id)
				->with('flash', 'A new test suite has been created');
		} else {
			return Redirect::to('/specification/')
				->withInput()
				->withErrors($testsuite->errors());
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
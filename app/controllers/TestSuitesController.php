<?php

use \Theme;
use \Input;
use \DB;
use Nestor\Repositories\TestSuiteRepository;
use Nestor\Repositories\TestCaseRepository;
use Nestor\Repositories\NavigationTreeRepository;
use Nestor\Repositories\LabelRepository;
use Fhaculty\Graph\Graph as Graph;
use Fhaculty\Graph\Algorithm\Search\BreadthFirst;
//use \Fhaculty\Graph\GraphViz;
use Fhaculty\Graph\Walk;

class TestSuitesController extends NavigationTreeController {

	/**
	 * The test suite repository implementation.
	 *
	 * @var Nestor\Repositories\TestSuiteRepository
	 */
	protected $testsuites;

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

	/**
	 * The labels repository implementation.
	 *
	 * @var Nestor\Repositories\LabelRepository
	 */
	protected $labels;

	protected $theme;

	public $restful = true;

	public function __construct(
		TestSuiteRepository $testsuites, 
		TestCaseRepository $testcases, 
		NavigationTreeRepository $nodes,
		LabelRepository $labels)
	{
		parent::__construct();
		$this->testsuites = $testsuites;
		$this->testcases = $testcases;
		$this->nodes = $nodes;
		$this->labels = $labels;
		$this->theme->setActive('testsuites');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return Redirect::to('/specification');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return Redirect::to('/specification');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$currentProject = $this->getCurrentProject();
		$labels = $this->labels->all($currentProject->id)->get();
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

			Log::debug('Processing test suite labels');
			$newLabels = Input::get('labels');
			if (isset($newLabels) && is_array($newLabels))
			{
				for($i = 0; $i < count($newLabels); ++$i)
				{
					$labelName = $newLabels[$i];
					$found = FALSE;
					foreach ($labels as $existingLabel)
					{
						if ($labelName == $existingLabel->name)
						{
							$found = TRUE;
							$label = $existingLabel;
							break;
						}
					}
					if (!$found)
					{
						$label = $this->labels->create($currentProject->id, $labelName, 'gray');
					}
					$testsuite->labels()->attach($label->id);
					Log::debug(sprintf('Label %s added to test suite id %d', $labelName, $testsuite->id));
				}
			}

			Log::debug('Inserting test suite into navigation tree');
			$ancestor = Input::get('ancestor');
			if ($testsuite->isValid() && $testsuite->isSaved())
			{
				$navigationTreeNode = $this->nodes->create(
						$ancestor,
						'2-' . $testsuite->id,
						$testsuite->id,
						2,
						$testsuite->name
				);
				if ($navigationTreeNode)
				{
					$pdo->commit();
				}
			}
			else
			{
				throw new Exception('Failed to create test case. Rolling back transaction');
			}
		} 
		catch (\PDOException $e) 
		{
			if (!is_null($pdo))
				$pdo->rollBack();
			return Redirect::to(URL::previous())
	 			->withInput();
		} 
		catch (\Exception $e) 
		{
			if (!is_null($pdo))
				$pdo->rollBack();
			Log::warning('Failed to store new Test Suite. Error: ' . $e->getMessage());
			$messages = new Illuminate\Support\MessageBag;
			$messages->add('nestor.customError', $e->getMessage());
			return Redirect::to('/specification/')->withInput()->withErrors($messages);
		}
		if ($testsuite->isSaved() && $navigationTreeNode)
		{
			Log::debug(sprintf('Test suite %s created!', $testsuite->name));
			return Redirect::to('/specification/nodes/' . '2-' . $testsuite->id)
				->with('success', 'A new test suite has been created');
		} else {
			Log::debug('Failed to create test suite and insert into navigationt tree.');
			return Redirect::to(URL::previous())
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
		$testsuite = $this->testsuites->find($id);
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Specification', URL::to('/specification'))->
			add(sprintf('Test Suite %s', $testsuite->name));
		$args = array();
		$labels = $testsuite->labels()->get();
		$args['testsuite'] = $testsuite;
		$args['labels'] = $labels;
		return $this->theme->scope('testsuite.show', $args)->render();
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$testsuite = $this->testsuites->find($id);
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Specification', URL::to('/specification'))->
			add(sprintf('Test Suite %s', $testsuite->name));
		$args = array();
		$labels = $testsuite->labels()->get();
		$args['testsuite'] = $testsuite;
		$args['labels'] = $labels;
		return $this->theme->scope('testsuite.edit', $args)->render();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$currentProject = $this->getCurrentProject();
		$labels = $this->labels->all($currentProject->id)->get();
		$testsuite = null;
		$navigationTreeNode = null;
		Log::info('Updating test suite...');
		$pdo = null;
		try {
			$pdo = DB::connection()->getPdo();
			$pdo->beginTransaction();
			$testsuite = $this->testsuites->update(
							$id,
							Input::get('project_id'),
							Input::get('name'),
							Input::get('description'));

			$existingLabels = $testsuite->labels()->get();
			$theLabels = Input::get('labels');
			if (isset($theLabels) && is_array($theLabels))
			{
				for($i = 0; $i < count($theLabels); ++$i)
				{
					$labelName = $theLabels[$i];
					$found = FALSE;
					foreach ($labels as $projectLabel)
					{
						if ($labelName == $projectLabel->name)
						{
							$found = TRUE;
							$label = $projectLabel;
							break;
						}
					}
					if (!$found)
					{
						$label = $this->labels->create($currentProject->id, $labelName, 'gray');
					}
					$found = FALSE;
					foreach ($existingLabels as $existingLabel)
					{
						if ($labelName == $existingLabel->name)
						{
							$found = TRUE;
							$label = $existingLabel;
							break;
						}
					}
					if (!$found)
					{
						$testsuite->labels()->attach($label->id);
						Log::debug(sprintf('Label %s added to test suite id %d', $labelName, $testsuite->id));
					}
				}
			}
			if (empty($theLabels))
			{
				foreach ($existingLabels as $existingLabel)
				{
					$testsuite->labels()->dettach($existingLabel->id);
				}
			}
			else
			{
				top2: foreach ($existingLabels as $existingLabel) 
				{
					foreach ($theLabels as $theLabel)
					{
						if ($theLabel == $existingLabel->name)
						{
							continue 2;
						}
					}
					Log::info("Deleting label " . $existingLabel->id);
					$testsuite->labels()->detach($existingLabel->id);
				}
			}

			if ($testsuite->isValid() && $testsuite->isSaved())
			{
				$navigationTreeNode = $this->nodes->updateDisplayNameByDescendant(
						'2-'.$testsuite->id,
						$testsuite->name);
				$pdo->commit();
			}
		} catch (\PDOException $e) {
			if (!is_null($pdo))
				$pdo->rollBack();
			return Redirect::to('/specification/')->withInput();
		}

		if ($testsuite->isSaved())
		{
			return Redirect::route('testsuites.show', $id)->with('success', 'The test suite was updated');
		} else {
			return Redirect::route('testsuites.edit', $id)
				->withInput()
				->withErrors($testsuite->errors());
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
		$testsuite = null;
		$navigationTreeNode = null;
		Log::info('Destroying test suite...');
		$pdo = null;
		try {
			$pdo = DB::connection()->getPdo();
			$pdo->beginTransaction();
			$testsuite = $this->testsuites->find($id);
			$this->testsuites->delete($id);
			$navigationTreeNode = $this->nodes->find('2-' . $testsuite->id, '2-' . $testsuite->id);
			$this->nodes->deleteWithAllChildren($navigationTreeNode->ancestor, $navigationTreeNode->descendant);
			$pdo->commit();
		} catch (\PDOException $e) {
			if (!is_null($pdo))
				$pdo->rollBack();
			return Redirect::to('/specification/')->withInput();
		}

		return Redirect::to('/specification')
			->with('success', sprintf('The test suite %s has been deleted', $testsuite->name));
	}

	public function postCopy()
	{
		$from = Input::get('copy_name');
		$to = Input::get('copy_new_name');
		$currentProject = $this->getCurrentProject();
		$ancestor = Input::get('ancestor');
		$navigationTreeNode = NULL;

		Log::info(sprintf('Copying test suite %s into %s', $from, $to));

		$pdo = null;
		try {
			$pdo = DB::connection()->getPdo();
			$pdo->beginTransaction();
			list($old, $testsuite) = $this->testsuites->copy($from, $to, $this->testcases);
			Log::info(sprintf('Test suite %s copied successfully into %s', $from, $to));
			Log::debug('Inserting test suite into navigation tree...');
			$navigationTreeNode = $this->nodes->create(
				$ancestor,
				'2-' . $testsuite->id,
				$testsuite->id,
				2,
				$testsuite->name
			);

			if ($navigationTreeNode)
			{
				// copy the children nodes
				$children = $this->nodes->children('2-' . $old->id);
				// foreach ($children as $child)
				// {
				// 	if ($child->ancestor == $child->descendant && !($child->node_type_id == 2 && $child->node_id == $old->id))
				// 		Log::info('COPY ME!');
				// 	// FIXME
				// }
				//$pdo->commit();

				$tree = $this->createNavigationTree($children, '2-' . $old->id);
				dd($tree);
			}
			$pdo->rollBack();
			dd('ok');
		} catch (\PDOException $e) {
			if (!is_null($pdo))
				$pdo->rollBack();
			return Redirect::to('/specification/nodes/1-'.$currentProject->id)->withInput();
		}

		return Redirect::to('/specification/nodes/2-' . $testsuite->id)
			->with('success', sprintf('The test suite %s has been copied into %s', $from, $to));
	}

	private function copyNodes($nodes, $ancestor)
	{
		foreach ($nodes->children as $node) 
		{
			if ($node->node_type_id == 2) 
			{ 
				// copy child test suite

				// insert it under the ancestor
			} 
			else if ($node->node_type_id == 3) 
			{
				// copy child test case

				// insert it under the ancestor
			}

			if (!empty($node->children)) 
			{
				// the inserted node is the new ancestor
				$this->copyNodes($node->children, NULL /* FIXME */);
			}
		}
	}

}
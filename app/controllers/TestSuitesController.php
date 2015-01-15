<?php

use Nestor\Repositories\TestSuiteRepository;
use Nestor\Repositories\TestCaseRepository;
use Nestor\Repositories\TestCaseStepRepository;
use Nestor\Repositories\NavigationTreeRepository;
use Nestor\Repositories\LabelRepository;
use Nestor\Model\Nodes;

use Fhaculty\Graph\Graph as Graph;
use Fhaculty\Graph\Algorithm\Search\BreadthFirst;
//use \Fhaculty\Graph\GraphViz;
use Fhaculty\Graph\Walk;

class TestSuitesController extends NavigationTreeController {

	protected $theme;

	public $restful = true;

	public function __construct()
	{
		parent::__construct();
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
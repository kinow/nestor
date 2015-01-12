<?php
namespace Nestor\Repositories;

use Auth;
use Hash;
use Validator;
use Log;
use DB;
use Label;

use Nestor\Model\TestSuite;
use Nestor\Util\NavigationTreeUtil;

use Fhaculty\Graph\Graph as Graph;
use Fhaculty\Graph\Algorithm\Search\BreadthFirst;

class DbTestSuiteRepository extends DbBaseRepository implements TestSuiteRepository
{
	public function __construct(TestSuite $model)
	{
		parent::__construct($model);
	}

	public function findByProject($projectId)
	{
		return $this
			->model
			->where('project_id', $projectId)
			->get()
			->toArray();
	}

	public function addLabels($id, $labels) 
	{
		foreach($labels as $label) {
			$this
				->model
				->find($id)
				->labels()
				->attach($label['id']);
			Log::debug(sprintf('Label %s added to test suite %d', $label['name'], $id));
		}
	}

	public function copy($oldName, $newName, $ancestor, $projectId, $nodesRepository, $testcaseRepository, $testcaseSteps)
	{
		$testsuite  = $this->findByName($oldName, $projectId);
		$oldTestsuite = $testsuite;
		$testsuiteCreated = NULL;
		$children 	= $nodesRepository->children('2-' . $testsuite->id);
		$graph 		= NavigationTreeUtil::createGraph($children);
		// copy the children nodes
		$rootVertex = new BreadthFirst($graph->getVertices()->getVertexId('2-'.$testsuite->id));
		$bfsVertices = $rootVertex->getVertices();
		$map = array(); // map with old => new for node ids
		foreach ($rootVertex->getVertices() as $vertex)
		{
			$node = $vertex->data;
			$parentEdge = $vertex->getVerticesEdgeFrom();
			$vertices = $parentEdge->getVertices()->getVector();
			if (empty($vertices))
			{
				// the root node
				Log::debug('Root node: ' . $node->descendant);
				$newTestsuite = $this->create($testsuite->project_id, $newName, $testsuite->description);
				$labels = $testsuite->labels()->get();
				foreach ($labels as $label)
				{
					$newTestsuite->labels()->attach($label->id);
				}
				Log::debug('Inserting cloned test suite into navigation tree...');
				$newNode = $nodesRepository->create($ancestor, '2-' . $newTestsuite->id, $newTestsuite->id, 2, $newTestsuite->name);
				$map[$node->descendant] = $newNode;
				$testsuiteCreated = $newTestsuite;
			}
			else if (count($vertices) > 1)
			{
				// inconsistent tree, raise exception
				Log::warning('Inconsistent tree!');
				throw new Exception('Inconsitent tree generated from graph. getVerticesEdgeFrom returned more than 1 vertex');
			}
			else
			{
				$parentNode = $map[$vertices[0]->data->descendant];
				foreach ($map as $key => $value) 
				{
					Log::debug('### ' . $key . ' => ' . $value->node_id);
				}

				// a node to be cloned
				Log::debug('Create node for ' . $node->descendant . ' parent ' . $parentNode->descendant);
				if ($node->node_type_id == 2) 
				{
					$testsuite = $this->find($node->node_id);
					// copy child test suite
					$newTestsuite = $this->create($testsuite->project_id, $testsuite->name, $testsuite->description);
					$labels = $testsuite->labels()->get();
					foreach ($labels as $label)
					{
						$newTestsuite->labels()->attach($label->id);
					}
					// insert it under the ancestor
					Log::debug('Inserting cloned test suite into navigation tree...');
					$newNode = $nodesRepository->create($parentNode->descendant, '2-' . $newTestsuite->id, $newTestsuite->id, 2, $newTestsuite->name);
					$map[$node->descendant] = $newNode;
				} 
				else if ($node->node_type_id == 3) 
				{
					$testcase = $testcaseRepository->find($node->node_id);
					$version = $testcase->latestVersion();
					// copy child test case
					list($newTestcase, $newTestcaseVersion) = $testcaseRepository->create($testcase->project_id, $parentNode->node_id, $version->execution_type_id, $version->name, $version->description, $version->prerequisite);
					$labels = $version->labels()->get();
					foreach ($labels as $label) 
					{
						$newTestcaseVersion->labels()->attach($label->id);
					}
					$steps = $version->sortedSteps()->get();
					foreach ($steps as $step)
					{
						list($testcaseStep, $testcaseStepVersion) = $testcaseSteps->create($newTestcaseVersion->id, $step->order, $step->description, $step->expected_result, $step->execution_status_id);
					}
					Log::debug('Inserting cloned test case into navigation tree...');
					$newNode = $nodesRepository->create($parentNode->descendant, '3-' . $newTestcase->id, $newTestcase->id, 3, $newTestcaseVersion->name);
					$map[$node->descendant] = $newNode;
				}
			}
		}

		return array($oldTestsuite, $testsuiteCreated);
	}

}

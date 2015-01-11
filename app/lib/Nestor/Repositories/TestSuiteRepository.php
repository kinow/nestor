<?php
namespace Nestor\Repositories;

interface TestSuiteRepository {

	public function findByProject($projectId);

	public function addLabels($id, $labels);

	public function copy($oldName, $newName, $ancestor, $projectId, $nodesRepository, $testcaseRepository, $testcaseSteps);

}
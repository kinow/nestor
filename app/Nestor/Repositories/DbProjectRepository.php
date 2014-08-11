<?php namespace Nestor\Repositories;

use Nestor\Model\Project;

class DbProjectRepository extends DbBaseRepository implements ProjectRepositoryInterface {

	public function __construct(Project $model)
	{
		parent::__construct($model);
	}

	public function deactivate($id)
	{
		$project = $this->find($id);
		$project->fill(array('project_statuses_id' => 2))->save(); // TODO: use constants
		return $project;
	}	
}

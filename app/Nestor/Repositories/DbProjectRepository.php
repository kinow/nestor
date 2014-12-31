<?php namespace Nestor\Repositories;

use Nestor\Model\Project;
use Nestor\Model\ProjectStatus;

class DbProjectRepository extends DbBaseRepository implements ProjectRepository {

	public function __construct(Project $model)
	{
		parent::__construct($model);
	}

	public function deactivate($id)
	{
		$project = $this->find($id);
		$project->fill(array('project_statuses_id' => ProjectStatus::INACTIVE))->save();
		return $project;
	}

	public function paginateProjectsWithProjectStatusWith($projectStatusId, $perPage = 10, array $with)
	{
		return $this
			->model
			->where('project_statuses_id', '=', $projectStatusId)
			->with($with)
			->paginate($perPage)
			->toArray();
	}

	public function allWithProjectStatus($projectStatusId)
	{
		return $this
			->model
			->where('project_statuses_id', '=', $projectStatusId)
			->get()
			->toArray();
	}

}

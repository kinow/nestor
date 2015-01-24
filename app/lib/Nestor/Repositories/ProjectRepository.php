<?php namespace Nestor\Repositories;

interface ProjectRepository {

	public function deactivate($id);

	public function paginateProjectsWithProjectStatusWith($perPage = 10, $projectStatusId, array $with);

	public function allWithProjectStatus($projectStatusId);

}
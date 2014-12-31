<?php namespace Nestor\Repositories;

interface ProjectRepository {

	public function deactivate($id);

	public function paginateProjectsWithProjectStatusWith($projectStatusId, $perPage = 10, array $with);

	public function allWithProjectStatus($projectStatusId);

}
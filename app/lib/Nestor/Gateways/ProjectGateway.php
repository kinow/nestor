<?php 
namespace Nestor\Gateways;

use Nestor\Repositories\ProjectRepository;
use Nestor\Model\ProjectStatus;

class ProjectGateway 
{

	protected $projectRepository;

	public function __construct(ProjectRepository $projectRepository) 
	{
		$this->projectRepository = $projectRepository;
	}

	public function paginateActiveProjects($perPage) 
	{
		$projects = $this
			->projectRepository
			->paginateProjectsWithProjectStatusWith(ProjectStatus::ACTIVE, $perPage, array('projectStatus'));
		return $projects;
	}
}
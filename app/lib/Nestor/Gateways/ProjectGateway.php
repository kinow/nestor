<?php 
namespace Nestor\Gateways;

use Exception;

use DB;
use Log;
use Session;

use Nestor\Repositories\ProjectRepository;
use Nestor\Repositories\NavigationTreeRepository;
use Nestor\Model\ProjectStatus;
use Nestor\Model\Nodes;

class ProjectGateway 
{

	protected $projectRepository;
	protected $nodeRepository;

	public function __construct(
		ProjectRepository $projectRepository,
		NavigationTreeRepository $nodeRepository) 
	{
		$this->projectRepository = $projectRepository;
		$this->nodeRepository = $nodeRepository;
	}

	public function paginateActiveProjects($perPage) 
	{
		$projects = $this
			->projectRepository
			->paginateProjectsWithProjectStatusWith($perPage, ProjectStatus::ACTIVE, array('projectStatus'));
		return $projects;
	}

	public function findProject($id) 
	{
		$project = $this->projectRepository->findWith($id, array('projectStatus'));
		return $project;
	}

	public function createProject($name, $description) 
	{
		DB::beginTransaction();
		$project = NULL;
		try {
			Log::debug('Creating project...');
			$project = $this->projectRepository->create(array(
				'name' => $name,
				'description' => $description,
				'project_statuses_id' => ProjectStatus::ACTIVE
			));
			Log::debug('Inserting project into the navigation tree...');

			$node = $this->nodeRepository->create(
				Nodes::id(Nodes::PROJECT_TYPE, $project['id']),
				Nodes::id(Nodes::PROJECT_TYPE, $project['id']),
				$project['id'],
				Nodes::PROJECT_TYPE,
				$project['name']
			);

			Log::info(sprintf('New node %s inserted into the navigation tree', $node['node_id']));
			DB::commit();
			return $project;
		} catch (Exception $e) {
			Log::error($e);
			DB::rollback();
			throw $e;
		}
	}

	public function updateProject($id, $name, $description)
	{
		DB::beginTransaction();
		try {
			Log::debug('Updating project...');
			$project = $this->projectRepository->update(
				$id,
				array(
					'name' => $name,
					'description' => $description
				)
			);

			Log::debug('Updating project in the navigation tree...');
			$node = $this->nodeRepository->update(
				Nodes::id(Nodes::PROJECT_TYPE, $id),
				Nodes::id(Nodes::PROJECT_TYPE, $id),
				$id,
				Nodes::PROJECT_TYPE,
				$name
			);

			Log::info(sprintf('Node %s updated in the navigation tree', $node['node_id']));
			DB::commit();
			return $project;
		} catch (Exception $e) {
			DB::rollback();
			throw $e;
		}
	}

	public function positionProject($projectId) 
	{
		try {
			$project = $this->projectRepository->find($projectId);
			Session::put('current_project', serialize($project));
			return TRUE;
		} catch (Exception $e) {
			Log::error($e);
			Session::forget('current_project');
			return FALSE;
		}
	}

}
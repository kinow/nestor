<?php 
namespace Nestor\Gateways;

use Nestor\Repositories\ProjectRepository;
use Nestor\Model\ProjectStatus;
use \DB;
use \Log;

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

	public function findProject($id) 
	{
		$project = $this->projectRepository->findWith($id, array('projectStatus'));
		return $project;
	}

	public function createProject($projectArray) 
	{
		DB::beginTransaction();
		$project = NULL;
		try {
			$project = $this->projectRepository->create($projectArray);
			Log::debug('Creating project...');
			// FIXME: insert into tree!
			// 		$navigationTreeNode = $this->nodes->create(
			// 				'1-' . $pdo->lastInsertId(),
			// 				'1-' . $pdo->lastInsertId(),
			// 				$pdo->lastInsertId(),
			// 				1,
			// 				$project->name
			// 		);
			DB::commit();
			return $project;
		} catch (\ValidationException $ve) {
			DB::rollback();
			//return Redirect::to(URL::previous())->withInput()->withErrors($ve->getErrors());
			throw $ve;
		} catch (\Exception $e) {
			DB::rollback();
			throw $e;
		}
	}

	public function updateProject($id, $name, $description)
	{
		Log::info('Updating project...');
		DB::beginTransaction();
		try {
			$project = $this->projectRepository->update(
				$id,
				array(
					'name' => $name,
					'description' => $description
				)
			);

			Log::info('Updated!!!');

			Log::info('Updating navigation tree...');
			// $navigationTreeNode = $this->nodes->find('1-'.$project->id, '1-'.$project->id);
			// $navigationTreeNode->display_name = $project->name;
			// $navigationTreeNode = $this->nodes->update(
			// 	'1-'.$project->id,
			// 	'1-'.$project->id,
			// 	$navigationTreeNode->node_id,
			// 	$navigationTreeNode->node_type_id,
			// 	$navigationTreeNode->display_name
			// );
			
			DB::commit();

			Log::info('Updated!!!');

			return $project;
		} catch (\PDOException $pe) {
			DB::rollback();
			throw $pe;
		} catch (ValidationException $ve) {
			DB::rollback();
			throw $ve;
		} catch (Exception $e) {
			DB::rollback();
			throw $e;
		}
	}

	
}
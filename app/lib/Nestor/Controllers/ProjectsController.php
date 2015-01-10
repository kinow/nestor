<?php
namespace Nestor\Controllers;

use BaseController;
use Input;
use Restable;
use Log;
use Db;
use Nestor\Util\ValidationException;
use Exception;
use Nestor\Gateways\ProjectGateway;

class ProjectsController extends BaseController
{

	public function __construct(ProjectGateway $projectGateway)
	{
		$this->projectGateway = $projectGateway;
	}

	public function index() 
	{
		$projects = $this
			->projectGateway
			->paginateActiveProjects(10);
		return Restable::listing($projects)->render();
	}

	public function show($id)
	{
		$project = $this
			->projectGateway
			->findProject($id);
		return Restable::single($project)->render();
	}

	public function store()
	{
		try {
			$project = $this
				->projectGateway
				->createProject(Input::all());
		} catch (ValidationException $ve) {
			return Restable::error($ve->getErrors())->render();
		} catch (Exception $e) {
			DB::rollback();
			throw $e;
		}
		return Restable::created($project)->render();
	}

	public function update($id)
	{
		try {
			$project = $this
				->projectGateway
				->updateProject($id, Input::get('name'), Input::get('description'));
		} catch (ValidationException $ve) {
			return Restable::error($ve->getErrors())->render();
		} catch (Exception $e) {
			DB::rollback();
			throw $e;
		}
		return Restable::updated($project)->render();
	}

}
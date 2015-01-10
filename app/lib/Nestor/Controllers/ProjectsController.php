<?php
namespace Nestor\Controllers;

use \BaseController;
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
		return \Restable::listing($projects)->render();
	}

}
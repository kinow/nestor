<?php
namespace Nestor\Controllers;

use BaseController;
use Restable;

use Nestor\Repositories\ExecutionTypeRepository;

class ExecutionTypesController extends BaseController 
{

	protected $executionTypeRepository;

	public function __construct(ExecutionTypeRepository $executionTypeRepository)
	{
		$this->executionTypeRepository = $executionTypeRepository;
	}

	public function index()
	{
		$executionTypes = $this->executionTypeRepository->all();
		return Restable::listing($executionTypes)->render();
	}

	public function show($id)
	{
		$executionType = $this->executionTypeRepository->find($id);
		return Restable::single($executionType)->render();
	}

}
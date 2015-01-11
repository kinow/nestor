<?php
namespace Nestor\Controllers;

use BaseController;
use Restable;

use Nestor\Repositories\ExecutionStatusRepository;

class ExecutionStatusesController extends BaseController 
{

	protected $executionStatusRepository;

	public function __construct(ExecutionStatusRepository $executionStatusRepository)
	{
		$this->executionStatusRepository = $executionStatusRepository;
	}

	public function index()
	{
		$executionStatuses = $this->executionStatusRepository->all();
		return Restable::listing($executionStatuses)->render();
	}

	public function show($id)
	{
		$executionStatus = $this->executionStatusRepository->find($id);
		return Restable::single($executionStatus)->render();
	}

}
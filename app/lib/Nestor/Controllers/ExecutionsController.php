<?php
namespace Nestor\Controllers;

use Input;
use Log;
use BaseController;
use Restable;

use Nestor\Gateways\ExecutionGateway;

class ExecutionsController extends BaseController 
{

	protected $executionGateway;

	public function __construct(ExecutionGateway $executionGateway)
	{
		$this->executionGateway = $executionGateway;
	}

	public function index()
	{
		$executions = $this->executionGateway->all();
		return Restable::listing($executions)->render();
	}

	public function show($id)
	{
		$executions = $this->executionGateway->find($id);
		return Restable::single($executions)->render();
	}

	public function store()
	{
		$execution = NULL;
		try {
			$execution = $this
				->executionGateway
				->createExecution(Input::get('test_plan_id'),
					Input::get('name'), Input::get('description'));
		} catch (ValidationException $ve) {
			return Restable::error($ve->getErrors())->render();
		} catch (Exception $e) {
			return Restable::bad($e->getMessage())->render();
		}
		return Restable::created($execution)->render();
	}

	public function update($id)
	{
		try {
			$testRun = $this
				->executionGateway
				->updateExecution($id, Input::get('test_plan_id'),
					Input::get('name'), Input::get('description'));
		} catch (ValidationException $ve) {
			return Restable::error($ve->getErrors())->render();
		} catch (Exception $e) {
			return Restable::bad($e->getMessage())->render();
		}
		return Restable::updated($testRun)->render();		
	}

}
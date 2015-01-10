<?php
namespace Nestor\Controllers;

use BaseController;
use Restable;
use Nestor\Gateways\SpecificationGateway;

class NodesController extends BaseController
{

	protected $specificationGateway;

	public function __construct(SpecificationGateway $specificationGateway)
	{
		$this->specificationGateway = $specificationGateway;
	}

	public function index()
	{

	}

	public function show($nodeId)
	{
		$nodes = $this->specificationGateway->findNodes($nodeId);
		return Restable::listing($nodes)->render();
	}

}
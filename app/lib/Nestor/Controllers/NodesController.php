<?php
namespace Nestor\Controllers;

use BaseController;
use Restable;
use Nestor\Gateways\NodeGateway;

class NodesController extends BaseController
{

	protected $nodeGateway;

	public function __construct(NodeGateway $nodeGateway)
	{
		$this->nodeGateway = $nodeGateway;
	}

	public function index()
	{

	}

	public function show($nodeId)
	{
		$nodes = $this->nodeGateway->findNodes($nodeId);
		return Restable::listing($nodes)->render();
	}

}
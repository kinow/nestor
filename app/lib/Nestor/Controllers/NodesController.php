<?php
namespace Nestor\Controllers;

use Input;
use Log;
use Response;

use Restable;

use BaseController;

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

	public function move()
	{
		$descendant = Input::get('descendant');
		$ancestor = Input::get('ancestor');

		if ($descendant && $ancestor) {
			Log::debug(sprintf('Moving %s under %s', $descendant, $ancestor));

			try {
				$this->nodeGateway->moveNode($descendant, $ancestor);
				return Response::json('OK');
			} catch (Exception $e) {
				Log::error($e);
				return Response::json($e->getMessage());
			}
		}

		return Response::json('Empty request!');
	}

}
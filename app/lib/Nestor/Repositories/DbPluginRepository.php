<?php namespace Nestor\Repositories;

use Nestor\Model\Plugin;

class DbPluginRepository extends DbBaseRepository implements PluginRepository {

	public function __construct(Plugin $model)
	{
		parent::__construct($model);
	}

	public function installed()
	{
		return $this->model->where('status', '=', 'INSTALLED')
			->toArray();
	}

	public function findByName($name)
	{
		return Plugin::where('name', '=', $name)
			->first()->toArray();
	}

}
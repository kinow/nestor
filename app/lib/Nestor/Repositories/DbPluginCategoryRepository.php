<?php namespace Nestor\Repositories;

use Nestor\Model\PluginCategory;

class DbPluginCategoryRepository extends DbBaseRepository implements PluginCategoryRepository {

	public function __construct(PluginCategory $model)
	{
		parent::__construct($model);
	}

}

<?php namespace Nestor\Repositories;

use Nestor\Model\ProjectStatus;

class DbProjectStatusRepository extends DbBaseRepository implements ProjectStatusRepository {

	public function __construct(ProjectStatus $model)
	{
		parent::__construct($model);
	}
	
}

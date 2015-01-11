<?php namespace Nestor\Repositories;

use Nestor\Model\ExecutionType;

class DbExecutionTypeRepository extends DbBaseRepository implements ExecutionTypeRepository {

	public function __construct(ExecutionType $model)
	{
		parent::__construct($model);
	}

}

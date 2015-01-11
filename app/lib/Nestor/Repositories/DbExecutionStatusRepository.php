<?php namespace Nestor\Repositories;

use Nestor\Model\ExecutionStatus;

class DbExecutionStatusRepository extends DbBaseRepository implements ExecutionStatusRepository {

	public function __construct(ExecutionStatus $model)
	{
		parent::__construct($model);
	}

}

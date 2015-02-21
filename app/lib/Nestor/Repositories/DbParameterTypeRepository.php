<?php namespace Nestor\Repositories;

use Nestor\Model\ParameterType;

class DbParameterTypeRepository extends DbBaseRepository implements ParameterTypeRepository {

	public function __construct(ParameterType $model)
	{
		parent::__construct($model);
	}

}

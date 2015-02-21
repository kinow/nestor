<?php namespace Nestor\Repositories;

use Nestor\Model\Parameter;

class DbParameterRepository extends DbBaseRepository implements ParameterRepository {

	public function __construct(Parameter $model)
	{
		parent::__construct($model);
	}

}

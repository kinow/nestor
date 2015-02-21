<?php namespace Nestor\Repositories;

use Nestor\Model\ReportType;

class DbReportTypeRepository extends DbBaseRepository implements ReportTypeRepository {

	public function __construct(ReportType $model)
	{
		parent::__construct($model);
	}
	
}

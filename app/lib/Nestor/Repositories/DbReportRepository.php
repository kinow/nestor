<?php namespace Nestor\Repositories;

use Nestor\Model\Report;

class DbReportRepository extends DbBaseRepository implements ReportRepository {

	public function __construct(Report $model)
	{
		parent::__construct($model);
	}

}

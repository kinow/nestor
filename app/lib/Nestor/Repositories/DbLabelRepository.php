<?php namespace Nestor\Repositories;

use Nestor\Model\Label;

class DbLabelRepository extends DbBaseRepository implements LabelRepository {

	public function __construct(Label $model)
	{
		parent::__construct($model);
	}

	public function findByProject($projectId)
	{
		$labels = $this
			->model
			->where('project_id', $projectId)
			->get()
			->toArray();
		return $labels;
	}

}
<?php

use Nestor\Repositories\ProjectStatusRepository;
use Nestor\Repositories\ExecutionTypeRepository;

class DatabaseSeeder extends Seeder {

	protected $projectStatuses;

	protected $executionTypes;

	public function __construct(ProjectStatusRepository $projectStatuses,
						ExecutionTypeRepository $executionTypes)
	{
		$this->projectStatuses = $projectStatuses;
		$this->executionTypes = $executionTypes;
	}

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->projectStatuses->create('active', 'Active project');
		$this->projectStatuses->create('inactive', 'Inactive project');

		$this->executionTypes->create('manual', 'Manual test');
		$this->executionTypes->create('automated', 'Automated test');
	}

}
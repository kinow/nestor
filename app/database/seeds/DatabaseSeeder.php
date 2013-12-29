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
		$this->projectStatuses->create('Active', 'Active project');
		$this->projectStatuses->create('Inactive', 'Inactive project');

		$this->executionTypes->create('Manual', 'Manual test');
		$this->executionTypes->create('Automated', 'Automated test');
	}

}
<?php

use Nestor\Repositories\ProjectStatusRepository;
use Nestor\Repositories\ExecutionTypeRepository;
use Nestor\Repositories\ExecutionStatusRepository;

class DatabaseSeeder extends Seeder {

	protected $projectStatuses;

	protected $executionTypes;

	protected $executionStatuses;

	public function __construct(ProjectStatusRepository $projectStatuses,
						ExecutionTypeRepository $executionTypes,
						ExecutionStatusRepository $executionStatuses)
	{
		$this->projectStatuses = $projectStatuses;
		$this->executionTypes = $executionTypes;
		$this->executionStatuses = $executionStatuses;
	}

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->call('PluginSeeder');
		$this->call('ConfigurationSeeder');

		$this->projectStatuses->create('Active', 'Active project');
		$this->projectStatuses->create('Inactive', 'Inactive project');

		$this->executionTypes->create('Manual', 'Manual test');
		$this->executionTypes->create('Automated', 'Automated test');

		$this->executionStatuses->create('Not Run', 'A test case not run yet');
		$this->executionStatuses->create('Passed', 'A test case that passed');
		$this->executionStatuses->create('Failed', 'A test case that failed');
		$this->executionStatuses->create('Blocked', 'A test case that is blocked');
	}

}
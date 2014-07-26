<?php

use Nestor\Repositories\ProjectStatusRepository;
use Nestor\Repositories\ExecutionTypeRepository;
use Nestor\Repositories\ExecutionStatusRepository;
use Nestor\Repositories\ReportTypeRepository;

class DatabaseSeeder extends Seeder {

	protected $projectStatuses;

	protected $executionTypes;

	protected $executionStatuses;

	public function __construct(ProjectStatusRepository $projectStatuses,
		ExecutionTypeRepository $executionTypes,
		ExecutionStatusRepository $executionStatuses,
		ReportTypeRepository $reportTypes)
	{
		$this->projectStatuses = $projectStatuses;
		$this->executionTypes = $executionTypes;
		$this->executionStatuses = $executionStatuses;
		$this->reportTypes = $reportTypes;
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

		$this->reportTypes->create(1, 'SQL', 'A report created based on a SQL query');
		$this->reportTypes->create(2, 'PHP', 'A report created based on a PHP script');

		$this->call('SampleDataSeeder');
	}

}
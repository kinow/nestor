<?php

use Nestor\Repositories\ProjectStatusRepository;
use Nestor\Repositories\ExecutionTypeRepository;
use Nestor\Repositories\ExecutionStatusRepository;
use Nestor\Repositories\ReportTypeRepository;
use Nestor\Repositories\ParameterTypeRepository;
use Nestor\Model\ProjectStatus;

class DatabaseSeeder extends Seeder {

	protected $projectStatuses;

	protected $executionTypes;

	protected $executionStatuses;

	public function __construct(ProjectStatusRepository $projectStatuses,
		ExecutionTypeRepository $executionTypes,
		ExecutionStatusRepository $executionStatuses,
		ReportTypeRepository $reportTypes, 
		ParameterTypeRepository $parameterTypes)
	{
		$this->projectStatuses = $projectStatuses;
		$this->executionTypes = $executionTypes;
		$this->executionStatuses = $executionStatuses;
		$this->reportTypes = $reportTypes;
		$this->parameterTypes = $parameterTypes;
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

		$this->projectStatuses->create(array(
			'id' => ProjectStatus::ACTIVE, 'name' => 'Active', 'description' => 'Active project'
		));
		$this->projectStatuses->create(array(
			'id' => ProjectStatus::INACTIVE, 'name' => 'Inactive', 'description' => 'Inactive project'
		));

		$this->executionTypes->create(array(
			'id'=> 1,
			'name' => 'Manual', 
			'description' => 'Manual test'
		));
		$this->executionTypes->create(array(
			'id' => 2,
			'name' => 'Automated', 
			'description' => 'Automated test'
		));

		$this->executionStatuses->create(array(
			'id' => 1,
			'name' => 'Not Run', 
			'description' => 'A test case not run yet'
		));
		$this->executionStatuses->create(array(
			'id' => 2,
			'name' => 'Passed', 
			'description' => 'A test case that passed'
		));
		$this->executionStatuses->create(array(
			'id' => 3,
			'name' => 'Failed', 
			'description' => 'A test case that failed'
		));
		$this->executionStatuses->create(array(
			'id' => 4,
			'name' => 'Blocked', 
			'description' => 'A test case that is blocked'
		));

		$this->reportTypes->create(array(
			'id' => 1, 'name' => 'SQL', 'description' => 'A report created based on a SQL query'
		));
		$this->reportTypes->create(array(
			'id' => 2, 'name' => 'PHP', 'description' => 'A report created based on a PHP script'
		));

		$this->parameterTypes->create(array(
			'id' => 1, 'name' => 'Text'
		));
		$this->parameterTypes->create(array(
			'id' => 2, 'name' => 'Date'
		));
		$this->parameterTypes->create(array(
			'id' => 3, 'name' => 'Numeric'
		));

		$this->call('SampleDataSeeder');
	}

}
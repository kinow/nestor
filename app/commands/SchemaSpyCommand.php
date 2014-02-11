<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SchemaSpyCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'schemaspy';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Executes SchemaSpy to generate the database schema automatically. You will need Java and the SchemaSpy jar.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->info('Creating SchemaSpy');

		$jar = $this->option("jar");
		$dbtype = $this->option("dbtype");
		$output = $this->option("output");

		$commandLine = sprintf("java -jar %s -u none -t %s -o %s", $jar, $dbtype, $output);

		$this->info(sprintf("Command line: [%s]", $commandLine));

		exec($commandLine);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			//array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
			array('jar', 'jar', InputOption::VALUE_REQUIRED, 'SchemaSpy jar'),
			array('dbtype', 'db', InputOption::VALUE_REQUIRED, 'Database Type. If using SQLite, give the sqlite.properties path'), 
			array('output', 'o', InputOption::VALUE_REQUIRED, 'Output directory')
		);
	}

}

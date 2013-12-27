<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestCasesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('test_cases', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('test_suite_id');
			$table->foreign('test_suite_id')->references('id')->on('test_suites');
			$table->integer('project_id');
			$table->foreign('project_id')->references('id')->on('projects');
			$table->integer('execution_type_id');
			$table->foreign('execution_type_id')->references('id')->on('execution_types');
			$table->string('name');
			$table->string('description')->nullable();
			$table->timestamps();
			$table->unique(array('name', 'test_suite_id'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('test_cases');
	}

}

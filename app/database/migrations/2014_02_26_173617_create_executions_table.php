<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExecutionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('executions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('test_run_id');
			$table->foreign('test_run_id')->references('id')->on('test_runs');
			$table->integer('test_case_id');
			$table->foreign('test_case_id')->references('id')->on('test_cases');
			$table->integer('execution_status_id')->default(1);
			$table->foreign('execution_status_id')->references('id')->on('executions_statuses');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('executions');
	}

}

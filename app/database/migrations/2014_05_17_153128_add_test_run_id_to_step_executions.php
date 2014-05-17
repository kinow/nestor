<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTestRunIdToStepExecutions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('step_executions', function(Blueprint $table)
		{
			$table->integer('test_run_id')->default(0);
			$table->foreign('test_run_id')
				->references('id')
				->on('test_runs')
				->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('step_executions', function(Blueprint $table)
		{
			$table->dropColumn('test_run_id');
		});
	}

}

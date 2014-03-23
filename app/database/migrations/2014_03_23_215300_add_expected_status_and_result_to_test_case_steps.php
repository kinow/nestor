<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpectedStatusAndResultToTestCaseSteps extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('test_case_steps', function(Blueprint $table)
		{
			$table->text('expected_result', 200)->default('');
			$table->text('execution_status_id')->default(1);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('test_case_steps', function(Blueprint $table)
		{
			if (Schema::hasColumn('test_case_steps', 'expected_result'))
			{
				$table->dropColumn('expected_result');
			}
			$table->dropColumn('execution_status_id');
		});
	}

}
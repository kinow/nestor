<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAssigneeColumnToTestPlansTestCases extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('test_plans_test_cases', function(Blueprint $table)
		{
			$table->integer('assignee')->nullable();
			$table->foreign('assignee')->references('id')->on('users')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('test_plans_test_cases', function(Blueprint $table)
		{
			Schema::drop('assignee');
		});
	}

}

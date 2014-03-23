<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrerequisitesColumnToTestCases extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('test_cases', function(Blueprint $table)
		{
			$table->text('prerequisite', 200)->default('');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('test_cases', function(Blueprint $table)
		{
			$table->dropColumn('prerequisite');
		});
	}

}
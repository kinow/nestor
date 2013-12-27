<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestSuitesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('test_suites', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('description');
			$table->integer('project_id');
			$table->foreign('project_id')->references('id')->on('projects');
			$table->timestamps();
			$table->unique(array('id', 'name'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('test_suites');
	}

}

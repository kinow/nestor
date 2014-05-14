<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestRunsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('test_runs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('test_plan_id');
			$table->foreign('test_plan_id')
				->references('id')
				->on('test_plans')
				->onDelete('cascade');
			$table->string('name', 50);
			$table->string('description');
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
		Schema::drop('test_runs');
	}

}

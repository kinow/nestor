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
			$table->foreign('test_run_id')->refenreces('id')->on('test_runs');
			$table->integer('test_plan_id');
			$table->foreign('test_plan_id')->references('id')->on('test_plans');
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

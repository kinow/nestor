<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestCaseStepsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('test_case_steps', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('order');
			$table->text('description', 500);
			$table->integer('test_case_id');
			$table->foreign('test_case_id')
				->references('id')
				->on('test_cases')
				->onDelete('cascade');
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
		Schema::drop('test_case_steps');
	}

}

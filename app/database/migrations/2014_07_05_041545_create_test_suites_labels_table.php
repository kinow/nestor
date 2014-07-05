<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestSuitesLabelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('test_suites_labels', function(Blueprint $table)
		{
			$table->integer('test_suite_id');
			$table->foreign('test_suite_id')->references('id')->on('test_suites')->onDelete('cascade');
			$table->integer('label_id');
			$table->foreign('label_id')->references('id')->on('labels')->onDelete('cascade');
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
		Schema::drop('test_suites_labels');
	}

}

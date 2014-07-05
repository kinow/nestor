<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestCaseVersionsLabelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('test_case_versions_labels', function(Blueprint $table)
		{
			$table->integer('test_case_version_id');
			$table->foreign('test_case_version_id')->references('id')->on('test_case_versions')->onDelete('cascade');
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
		Schema::drop('test_case_versions_labels');
	}

}

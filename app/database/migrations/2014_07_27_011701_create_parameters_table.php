<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParametersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('parameters', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('parameter_type_id');
			$table->foreign('parameter_type_id')->references('id')->on('parameter_type')->onDelete('cascade');
			$table->integer('report_id');
			$table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
			$table->string('name');
			$table->unique(array('report_id', 'name'));
			$table->string('description', 100)->nullable();
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
		Schema::drop('parameters');
	}

}

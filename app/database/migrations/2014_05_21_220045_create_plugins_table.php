<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePluginsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('plugins', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 50)->unique();
			$table->string('slug', 50)->unique();
			$table->string('description', 200)->nullable();
			$table->string('version', 10);
			$table->string('author', 500);
			$table->string('url', 255)->nullable();
			$table->string('status', 20);
			$table->date('released_at');
			$table->integer('plugin_category_id');
			$table->foreign('plugin_category_id')
				->references('id')
				->on('plugin_categories')
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
		Schema::drop('plugins');
	}

}

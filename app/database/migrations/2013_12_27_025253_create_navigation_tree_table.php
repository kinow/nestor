<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNavigationTreeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('navigation_tree', function(Blueprint $table)
		{
			$table->integer('id');
			$table->primary('id');
			$table->integer('node_id');
			$table->integer('node_type_id');
			$table->foreign('node_type_id')->references('id')->on('navigation_tree_node_types');
			$table->integer('parent_id')->default(0);
			$table->string('display_name');
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
		Schema::drop('navigation_tree');
	}

}

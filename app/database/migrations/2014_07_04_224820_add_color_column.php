<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColorColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('labels', function(Blueprint $table)
		{
			$table->string('color', 20)->default('#EEEEEE');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('labels', function(Blueprint $table)
		{
			$table->dropColumn('color');
		});
	}

}

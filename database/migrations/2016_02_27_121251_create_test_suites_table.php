<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestSuitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_suites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('description', 1000)->nullable();
            $table->integer('project_id');
            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->onDelete('cascade');
            $table->integer('created_by');
            $table->foreign('created_by')
                ->references('id')
                ->on('users');
            $table->timestamps();
            $table->unique(array('project_id', 'name'));
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

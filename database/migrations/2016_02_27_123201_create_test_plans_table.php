<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id');
            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->onDelete('cascade');
            $table->string('name');
            $table->string('description')->nullable();
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
        Schema::drop('test_plans');
    }
}

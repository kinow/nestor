<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestCaseVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_cases_versions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('version');
            $table->integer('test_case_id');
            $table->foreign('test_case_id')
                ->references('id')
                ->on('test_cases')
                ->onDelete('cascade');
            $table->integer('execution_type_id');
            $table->foreign('execution_type_id')
                ->references('id')
                ->on('execution_types')
                ->onDelete('cascade');
            $table->string('name', 255);
            $table->string('prerequisite', 1000)->nullable();
            $table->string('description', 1000)->nullable();
            $table->timestamps();
            $table->unique(array('version', 'test_case_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('test_cases_versions');
    }
}

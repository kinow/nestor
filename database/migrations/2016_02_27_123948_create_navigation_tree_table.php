<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNavigationTreeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigation_tree', function (Blueprint $table) {
            $table->string('ancestor', 20);
            $table->string('descendant', 20);
            $table->integer('length');
            $table->integer('node_id');
            $table->integer('node_type_id');
            $table->foreign('node_type_id')->references('id')->on('navigation_tree_node_types');
            $table->string('display_name', 255);
            $table->text('attributes')->nullable();
            $table->timestamps();
            $table->primary(array (
                'ancestor',
                'descendant'
            ));
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

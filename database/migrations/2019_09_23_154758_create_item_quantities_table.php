<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemQuantitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_item_quantities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('in')->default(0);
            $table->integer('out')->default(0);
            $table->integer('condemned')->default(0);
            $table->integer('balance')->default(0);
            $table->timestamps();
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('office_id');
             // relationships
             $table->foreign('item_id')->references('id')->on('tbl_items');
             $table->foreign('office_id')->references('id')->on('tbl_offices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_item_quantities');
    }
}

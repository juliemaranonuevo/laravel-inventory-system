<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_item_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->text('value');
            $table->timestamps();
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('category_field_id');
             // relationships
             $table->foreign('item_id')->references('id')->on('tbl_items');
             $table->foreign('category_field_id')->references('id')->on('tbl_category_fields');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_item_fields');
    }
}

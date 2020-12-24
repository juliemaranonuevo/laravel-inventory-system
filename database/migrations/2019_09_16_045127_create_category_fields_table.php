<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::create('tbl_category_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('field_id');
            
            $table->foreign('category_id')->references('id')->on('tbl_categories');
            $table->foreign('field_id')->references('id')->on('tbl_fields');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_category_fields');
    }
}

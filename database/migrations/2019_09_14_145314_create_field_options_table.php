<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_field_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('option')->unique();
            $table->timestamps();
            $table->unsignedInteger('field_id');
            // relationships
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
        Schema::dropIfExists('tbl_field_options');
    }
}

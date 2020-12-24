<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStickersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_stickers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('office');
            $table->string('property_number')->unique();
            $table->text('article');
            $table->string('brand_sn')->unique()->nullable();
            $table->string('remarks')->nullable();
            $table->integer('date_count');
            $table->string('memo_receipt_employee');
            $table->string('type');
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
        Schema::dropIfExists('tbl_stickers');
    }
}

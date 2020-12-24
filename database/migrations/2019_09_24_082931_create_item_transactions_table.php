<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_item_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('property_number')->nullable();
            $table->string('type');
            $table->integer('quantity');
            $table->date('transaction_date');
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->unsignedInteger('item_quantity_id');
             // relationships
             $table->foreign('item_quantity_id')->references('id')->on('tbl_item_quantities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_item_transactions');
    }
}

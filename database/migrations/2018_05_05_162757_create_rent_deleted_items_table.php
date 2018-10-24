<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRentDeletedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rent_deleted_items', function (Blueprint $table) {
            $table->increments('id');
            $table->text('images');
            $table->integer('category');
            $table->integer('author');
            $table->string('phone_number');
            $table->string('messengers');
            $table->text('additional_info');
            $table->text('features');
            $table->integer('price');
            $table->string('payment_time');
            $table->string('address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rent_deleted_items');
    }
}

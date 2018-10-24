<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('icon')->nullable()->default(null);
            $table->text('background')->nullable()->default(null);
            $table->text('slider')->nullable()->default(null);
            $table->integer('administrator')->unsigned()->nullable()->default(null);
            $table->foreign('administrator')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->string('moderators');
            $table->integer('type')->unsigned()->nullable()->default(null);
            $table->text('categories')->nullable()->default(null);
            $table->text('address');
            $table->text('contacts')->nullable()->default(null);
            $table->boolean('is_sale')->nullable()->default(1);
            $table->text('delivery')->nullable()->default(null);
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
        Schema::dropIfExists('markets');
    }
}

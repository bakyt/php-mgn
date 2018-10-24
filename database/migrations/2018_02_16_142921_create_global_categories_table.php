<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlobalCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table for storing categories
        Schema::create('global_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned()->nullable()->default(null);
            $table->foreign('parent_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('set null');
            $table->tinyInteger('type')->default(0);
            $table->tinyInteger('state')->default(0);
            $table->integer('order')->default(1);
            $table->string('name');
            $table->text('description');
            $table->text('keywords');
            $table->string('image')->nullable()->default(null);
            $table->text('features')->nullable()->default(null);
            $table->tinyInteger('status')->default(1);
            $table->integer('author_id')->unsigned()->nullable()->default(null);
            $table->foreign('author_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->integer('moderator_id')->unsigned()->nullable()->default(null);
            $table->foreign('moderator_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
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
        Schema::dropIfExists('global_categories');
    }
}

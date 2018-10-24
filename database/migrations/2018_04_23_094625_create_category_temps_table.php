<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_temps', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('original_id')->unsigned()->nullable()->default(null);
            $table->integer('parent_id')->unsigned()->nullable()->default(null);
            $table->foreign('parent_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('set null');
            $table->integer('order')->default(1);
            $table->tinyInteger('type')->default(0);
            $table->string('name');
            $table->text('description');
            $table->text('features')->nullable()->default(null);;
            $table->string('image')->nullable()->default(null);
            $table->integer('author_id')->unsigned()->nullable()->default(null);
            $table->foreign('author_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
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
        Schema::dropIfExists('category_temps');
    }
}

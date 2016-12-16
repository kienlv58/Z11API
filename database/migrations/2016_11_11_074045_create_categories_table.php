<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories',function (Blueprint $table){
           $table->increments('category_id');
            $table->string('category_code')->index();
            $table->string('image')->nullable();
            $table->integer('name_text_id')->index()->unsigned();
            $table->integer('describe_text_id')->index()->unsigned();
            $table->integer('creator_id');
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
        Schema::dropIfExists('categories');
    }
}

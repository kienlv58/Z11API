<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFolderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->increments('folder_id');
            $table->string('item_code');//f
            $table->integer('category_id')->index()->unsigned();
            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('cascade');
            $table->integer('name_text_id')->index()->unsigned();
            $table->integer('describe_text_id')->index()->unsigned();
            $table->integer('owner_id')->index()->unsigned();
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
        Schema::dropIfExists('folders');
    }
}

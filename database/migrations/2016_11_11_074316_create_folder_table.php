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
            $table->increments('folder_id')->index();
            $table->string('item_code');
            $table->string('category_code')->index();
            $table->foreign('category_code')->references('category_code')->on('categories')->onDelete('cascade');
            $table->integer('name_text_id')->index()->unsigned();
            $table->integer('owner_id')->index()->unsigned();
            $table->string('type_owner')->index();
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

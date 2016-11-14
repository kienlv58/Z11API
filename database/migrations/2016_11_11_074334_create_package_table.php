<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages',function (Blueprint $table){
            $table->increments('package_id')->index();
            $table->string('item_code');
            $table->integer('folder_id')->index()->unsigned();
            $table->foreign('folder_id')->references('folder_id')->on('folders')->onDelete('cascade');
            $table->integer('create_user_id')->index()->unsigned();
            $table->integer('name_text_id')->index()->unsigned();
            $table->string('classify_id')->index();
            $table->string('supported_language');
            $table->integer('package_cost');
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
        Schema::dropIfExists('packages');
    }
}

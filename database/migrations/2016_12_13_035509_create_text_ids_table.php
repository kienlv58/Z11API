<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTextIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('text_ids', function (Blueprint $table) {
            $table->increments('text_id');
            $table->timestamps();
        });
        Schema::table('translates',function ($table){
            $table->foreign('text_id')->references('text_id')->on('text_ids')->onDelete('cascade');
        });
        Schema::table('categories',function ($table){
            $table->foreign('name_text_id')->references('text_id')->on('text_ids')->onDelete('cascade');
        });
        Schema::table('folders',function ($table) {
            $table->foreign('name_text_id')->references('text_id')->on('text_ids')->onDelete('cascade');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('packages',function ($table) {
            $table->foreign('name_text_id')->references('text_id')->on('text_ids')->onDelete('cascade');
        });
//        Schema::table('group_questions',function ($table) {
//            $table->foreign('explain_item_id')->references('explain_item_id')->on('explains')->onDelete('cascade');
//        });
//        Schema::table('questions',function ($table) {
//            $table->foreign('explain_item_id')->references('explain_item_id')->on('explains')->onDelete('cascade');
//        });
        Schema::table('explains',function ($table) {
            $table->foreign('explain_text_id')->references('text_id')->on('text_ids')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('text_ids');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExplainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('explains', function (Blueprint $table) {
            $table->increments('explain_id')->index();
            $table->string('item_code');
            $table->integer('explain_cost');
            $table->timestamps();
        });


        Schema::table('categories',function ($table){
            $table->foreign('name_text_id')->references('explain_id')->on('explains')->onDelete('cascade');
        });
        Schema::table('folders',function ($table) {
            $table->foreign('name_text_id')->references('explain_id')->on('explains')->onDelete('cascade');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('chapters',function ($table) {
            $table->foreign('name_text_id')->references('explain_id')->on('explains')->onDelete('cascade');
        });
        Schema::table('group_questions',function ($table) {
            $table->foreign('name_text_id')->references('explain_id')->on('explains')->onDelete('cascade');
        });
        Schema::table('questions',function ($table) {
            $table->foreign('name_text_id')->references('explain_id')->on('explains')->onDelete('cascade');
        });
        Schema::table('translates',function ($table) {
            $table->foreign('name_text_id')->references('explain_id')->on('explains')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('explains');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('folders');
        Schema::dropIfExists('chapters');
        Schema::dropIfExists('group_questions');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('translates');
    }
}

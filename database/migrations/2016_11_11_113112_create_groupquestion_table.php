<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupquestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_questions', function (Blueprint $table) {
            $table->increments('group_question_id');
            $table->string('item_code');//group_qs
            $table->integer('chapter_id')->index()->unsigned();
            $table->integer('explain_item_id')->index()->unsigned();
            $table->foreign('chapter_id')->references('chapter_id')->on('chapters')->onDelete('cascade');
            $table->string('group_question_content');
            $table->string('group_question_transcript')->nullable();
            $table->string('group_question_image')->nullable();
            $table->string('group_question_audio')->nullable();
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
        Schema::dropIfExists('group_questions');
    }
}

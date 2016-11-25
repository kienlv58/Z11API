<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('question_id')->index()->unsigned();
            $table->string('item_code');//question
            $table->integer('group_question_id')->index()->unsigned();
            $table->string('sub_question_content');
            $table->integer('explain_id')->index()->unsigned();
            $table->foreign('group_question_id')->references('group_question_id')->on('group_questions')->onDelete('cascade');
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
        Schema::dropIfExists('questions');
    }

}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer_item', function (Blueprint $table) {
            $table->increments('answer_item_id')->index();
            $table->string('item_code');
            $table->integer('question_id')->index()->unsigned();
            $table->string('answer_item_value');
            $table->integer('answer_is_correct');
            $table->foreign('question_id')->references('question_id')->on('questions')->onDelete('cascade');
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
        Schema::dropIfExists('answer_item');
    }
}

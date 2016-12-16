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
            $table->increments('explain_item_id');
            $table->string('item_code');//explain
            $table->integer('explain_cost');
            $table->integer('explain_text_id')->index()->unsigned();
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
        Schema::dropIfExists('explains');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('folders');
        Schema::dropIfExists('chapters');
        Schema::dropIfExists('group_questions');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('translates');
    }
}

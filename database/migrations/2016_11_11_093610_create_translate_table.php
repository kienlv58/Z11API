<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translates',function (Blueprint $table){
           $table->increments('translate_id')->index()->unsigned();
            $table->integer('name_text_id')->index()->unsigned();
            $table->string('language_id')->index();
            $table->string('text_value');
            $table->string('describe_value')->nullable();
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
        Schema::dropIfExists('translates');
    }
}



//------------------------------------------------------

//------------------------------------

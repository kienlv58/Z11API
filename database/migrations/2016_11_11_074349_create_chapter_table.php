<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChapterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapters',function (Blueprint $table){
            $table->increments('chapter_id');
            $table->string('item_code');//ch
            $table->integer('package_id')->index()->unsigned();
            $table->foreign('package_id')->references('package_id')->on('packages')->onDelete('cascade');
            $table->string('name_text');
            $table->string('describe_text');
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
        Schema::dropIfExists('chapters');
    }
}

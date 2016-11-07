<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProfilesTableUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('profiles',function (Blueprint $table){
          $table->increments('id');
           $table->integer('user_id');
           $table->string('image')->nullable();
           $table->string('gender')->nullable();
           $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::drop('profiles');
    }
}
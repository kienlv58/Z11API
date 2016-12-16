<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_roles', function (Blueprint $table) {
            $table->increments('session_role_id');
            $table->string('token',1000);
            $table->integer('user_id')->index();
            $table->string('expired');
            $table->timestamps();
        });
        Schema::table('user_roles',function ($table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('session_roles');
    }
}

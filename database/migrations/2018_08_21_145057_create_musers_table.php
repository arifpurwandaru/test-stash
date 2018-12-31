<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMusersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('musers', function (Blueprint $table) {
            $table->string("loginid",60);
            $table->string("username",100);
            $table->string("password",40);
            $table->string("email",30)->unique();
            $table->string("status_user",1)->nullable();
            $table->string("alamat", 200)->nullable();
            $table->string("imgLink", 100)->nullable();
            $table->string("imgLinkTemp",100)->nullable();
            $table->string("deviceId", 100)->nullable();
            $table->timestamps();
            $table->primary("loginid");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('musers');
    }
}

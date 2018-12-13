<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSesiPemeriksaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sesi_pemeriksaans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mjadwal_parent_id');
            $table->string('tempat',100);
            $table->string('mulai',10);
            $table->string('selesai',10);
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
        Schema::dropIfExists('sesi_pemeriksaans');
    }
}

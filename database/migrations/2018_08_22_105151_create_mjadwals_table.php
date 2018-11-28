<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMjadwalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mjadwals', function (Blueprint $table) {
            $table->increments('id');
            $table->date('tanggal');
            $table->string('tempat',100)->nullable();
            $table->string('mulai',10);
            $table->string('selesai',10);
            $table->string('keterangan',225)->nullable();
            $table->integer('mjadwal_parent_id');
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
        Schema::dropIfExists('mjadwals');
    }
}

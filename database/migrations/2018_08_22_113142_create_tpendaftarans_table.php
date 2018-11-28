<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTpendaftaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tpendaftarans', function (Blueprint $table) {
            $table->string('id',50);
            $table->string('pasienid',50);
            $table->string('loginid',60);
            $table->string('keluhan',100)->nullable();
            $table->string('status_kunjungan',1)->nullable();
            $table->string('cara_bayar',1)->nullable();
            $table->integer('no_urut');
            $table->string('status_antrian',1);
            $table->string('dokumen_list')->nullable();
            $table->string('catatan_medis',300)->nullable();
            $table->string('diagnosa_penyakit',300)->nullable();
            $table->string('obat',300)->nullable();
            $table->string('jadwal_pendaftaran')->nullable();
            $table->timestamps();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tpendaftarans');
    }
}

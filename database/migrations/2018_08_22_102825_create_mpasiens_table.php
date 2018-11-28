<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpasiensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mpasiens', function (Blueprint $table) {
            $table->string('pasienid',50);
            $table->string('loginid',50);
            $table->string('nama',50);
            $table->string('jenisKelamin',1);
            $table->string('nik',16);
            $table->string('email',225)->nullable();
            $table->date('tglLahir')->nullable();
            $table->integer('umur')->nullable();
            $table->string('golonganDarah',2)->nullable();
            $table->string('statusPernikahan',20)->nullable();
            $table->string('alamatLengkap',200);
            $table->string('pekerjaan',50)->nullable();
            $table->longText('imgLink')->nullable();
            $table->timestamps();
            $table->primary('pasienid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mpasiens');
    }
}

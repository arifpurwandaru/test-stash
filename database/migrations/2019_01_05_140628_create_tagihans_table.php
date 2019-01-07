<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagihansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tagihans', function (Blueprint $table) {
            $table->string('periode',30);
            $table->integer('jmlPasien');
            $table->decimal('jmlTagihan',12,0);
            $table->decimal('trxFee',9,0);
            $table->string('statusPembayaran',1);
            $table->timestamps();
            $table->primary('periode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tagihans');
    }
}

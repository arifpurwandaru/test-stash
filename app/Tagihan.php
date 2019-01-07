<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model{
    protected $primaryKey = 'periode';
    
    protected $fillable = ['periode', 'jmlPasien','jmlTagihan','statusPembayaran','trxFee'];

            protected $casts = [
                'periode' => 'string'
              ];
}

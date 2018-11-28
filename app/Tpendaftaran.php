<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tpendaftaran extends Model{
    protected $fillable = ['id','pasienid','loginid','keluhan',
            'status_kunjungan','cara_bayar','no_urut',
            'status_antrian','dokumen_list','catatan_medis',
            'jadwal_pendaftaran', 'diagnosa_penyakit','obat'];

            
            protected $casts = [
                'id' => 'string'
              ];

}

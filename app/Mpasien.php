<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mpasien extends Model
{
    protected $primaryKey = 'pasienid';
    
    protected $fillable = ['pasienid', 'nama','jenisKelamin','nik','loginid',
            'email','tglLahir','umur','golonganDarah',
            'statusPernikahan','alamatLengkap','pekerjaan', 'imgLink','imgLinkTemp'];

            protected $casts = [
                'pasienid' => 'string'
              ];
    public function muser(){
        return $this->belongsTo('App\Muser');
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mjadwal extends Model{

    protected $fillable=['tanggal','tempat',
                'mulai','selesai','keterangan', 'mjadwal_parent_id'];
                
    public function theParent(){
      return $this->belongsTo('App\MjadwalParent');
    }
}

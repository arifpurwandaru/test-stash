<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SesiPemeriksaan extends Model{
    
    protected $fillable = ['mjadwal_parent_id', 'tempat','mulai','selesai'];

    public function parent(){
        return $this->belongsTo('App\MjadwalParent');
      }

}

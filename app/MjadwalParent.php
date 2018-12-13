<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MjadwalParent extends Model{
    
    protected $primaryKey = 'id';

    protected $fillable=['id','hari'];

    protected $casts = [
        'id' => 'integer'
      ];
    public function jadwals(){
        return $this->hasMany('App\Mjadwal');
    }

    
    public function sesis(){
        return $this->hasMany('App\SesiPemeriksaan');
    }
}

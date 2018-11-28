<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MjadwalParent extends Model{
    
    protected $fillable=['hari','waktu'];

    public function jadwals(){
        return $this->hasMany('App\Mjadwal');
    }
}

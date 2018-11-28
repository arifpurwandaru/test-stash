<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Muser extends Model{
    public $primaryKey = 'loginid';
    protected $fillable = ['loginid', 'username','password','email','status_user','alamat','imgLink','deviceId'];

    protected $casts = [
        'loginid' => 'string'
      ];
    public function pasiens(){
        return $this->hasMany('App\Mpasien', 'loginid');
    }
}

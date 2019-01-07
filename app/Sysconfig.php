<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sysconfig extends Model
{
    protected $primaryKey = 'configKey';
    
    protected $fillable = ['configKey', 'configValue'];

            protected $casts = [
                'configKey' => 'string'
              ];
}

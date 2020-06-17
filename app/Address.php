<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public function cache(){
        return $this->hasOne('App\Cache');
    }
    protected $fillable=['index','tag','bo','cache_id','status'];
}

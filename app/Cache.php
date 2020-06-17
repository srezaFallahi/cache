<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cache extends Model
{
     public function memory(){
         return $this->hasOne('App\Memory');
     }
     protected $fillable=['bo_size','size',"index_size","tag_size",'cache_access_time','cache_miss_time','type','address_size'];

     public function addresses(){
         return $this->belongsToMany('App\Address');
     }
}

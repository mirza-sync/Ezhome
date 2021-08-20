<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    public function homestays(){
        return $this->belongsToMany('App\Homestay');
    }
}

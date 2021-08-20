<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public function homestay(){
        return $this->belongsTo('App\Homestay');
    }

    public function users(){
        return $this->belongsTo('App\User');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Homestay extends Model
{
    public function landlord(){
        return $this->belongsTo('App\Landlord');
    }

    public function booking(){
        return $this->hasMany('App\Booking');
    }

    public function facilities(){
        return $this->belongsToMany('App\Facility');
    }
}

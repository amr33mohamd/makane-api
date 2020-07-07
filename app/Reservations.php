<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservations extends Model
{
    protected  $guarded = [];

    // status 0 -> waiting 1->done 2->ignored
    public function store(){
        return  $this->hasOne('App\User','id','store_id');
    }
    public function user(){
        return  $this->hasOne('App\User','id','customer_id');
    }
}

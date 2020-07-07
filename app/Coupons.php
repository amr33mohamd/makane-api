<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    protected  $guarded = [];

    public function store(){
        return $this->hasOne('App\User','id','store_id');
    }
}

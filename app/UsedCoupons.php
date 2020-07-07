<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsedCoupons extends Model
{
    protected  $guarded = [];

    public function user(){
        return $this->hasOne('App\User','id','user_id');
    }
    public function coupon(){
        return $this->hasOne('App\Coupons','id','coupon_id');
    }
}

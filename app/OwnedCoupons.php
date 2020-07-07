<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OwnedCoupons extends Model
{
    protected  $guarded = [];
    public function coupon(){
        return  $this->hasOne('App\Coupons','id','coupon_id');
    }
    public function user(){
        return  $this->hasOne('App\User','id','user_id');
    }
}

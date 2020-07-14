<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservations extends Model
{
    protected  $guarded = [];
    protected $appends = ['clientReview','storeReview'];
    // status 0 -> waiting 1->done 2->ignored 3->canceled

    public function storeReview(){
        return  $this->hasOne('App\StoreReviews','reservation_id','id');
    }
    public function store(){
        return  $this->hasOne('App\User','id','store_id');
    }
    public function user(){
        return  $this->hasOne('App\User','id','customer_id');
    }
    public function special_event(){
            return $this->hasOne('App\SpecialEvents','id','SpecialEvent_id');
    }
    public function user_review(){
        return $this->hasOne('App\Reviews','reservation_id','id');
    }

    public function getClientReviewAttribute(){
        if ($this->user_review) {
            return true;
        }
        else{
            return false;
        }
    }
    public function getStoreReviewAttribute(){
        if(StoreReviews::where('reservation_id',$this->id)->count()>0){
            return true;
        }
        else{
            return false;
        }
    }
}

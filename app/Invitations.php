<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitations extends Model
{
    protected  $guarded = [];
    public function invited(){
        return $this->hasOne('App\User','id','invited_id');
    }
    public function inviter(){
        return $this->hasOne('App\User','id','inviter_id');
    }
}

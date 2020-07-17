<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    protected  $guarded = [];

    public function user(){
        return  $this->hasOne('App\User','id','user_id');
    }
}

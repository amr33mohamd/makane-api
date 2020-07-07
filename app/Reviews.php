<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    protected  $guarded = [];

    public function store(){
        return  $this->hasOne('App\User','id','store_id');
    }
    public function reviewer(){
        return  $this->hasOne('App\User','id','reviewer_id');
    }
}

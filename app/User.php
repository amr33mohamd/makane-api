<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $appends = ['rating'];


    protected $guarded = [];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function setPasswordAttribute($password)
    {
        if ( !empty($password) ) {
            $this->attributes['password'] = bcrypt($password);
        }
    }
    public function StoreCoupons(){
       return $this->hasMany('App\Coupons','store_id','id');
    }
    public function OwnedCoupons(){
        return $this->hasMany('App\OwnedCoupons','user_id','id');
    }
    public function UsedCoupons(){
        return $this->hasMany('App\UsedCoupons','user_id','id');
    }
    public function StoreReviews(){
        return $this->hasMany('App\Reviews','store_id','id');
    }
    public function UserReviews(){
        return $this->hasMany('App\StoreReviews','user_id','id');
    }
    public function ReviewerReviews(){
        return $this->hasMany('App\Reviews','reviewer_id','id');
    }
    public function SpecialEvents(){
        return $this->hasMany('App\SpecialEvents','store_id','id');
    }
    public function StoreImages(){
        return $this->hasMany('App\Images','user_id','id');
    }
    public function StoreReservations(){
        return $this->hasMany('App\Reservations','store_id','id');
    }
    public function UserReservations(){
        return $this->hasMany('App\Reservations','customer_id','id');
    }
    public function Inviter(){
        return $this->hasMany('App\Invitations','inviter_id','id');
    }
    public function Invited(){
        return $this->hasOne('App\Invitations','invited_id','id');
    }
    public function notify($title,$body){
      $SERVER_API_KEY = 'AAAATFeH_L4:APA91bGUvk9ZFani7HODMs1diRh_r-5XpEFyScZpYlb3wNjhvD3ujmta4Rgl_oKuzCIr9qlY0nDo1T0l5Ria4iluTPedVdPn2jHJhEjNHUARnO7whnS8UIRqT7lLRkuG2BCfJTG__BSm';

      $data = [
        "to"=>"/topics/$this->id",
          "notification" => [
              "title" => $title,
              "body" =>$body,
          ]
      ];
      $dataString = json_encode($data);

      $headers = [
          'Authorization: key=' . $SERVER_API_KEY,
          'Content-Type: application/json',
      ];

      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

      $response = curl_exec($ch);
      return true;
    }
    public function getRatingAttribute()
    {
        if($this->type == 2){
            if($this->StoreReviews->avg('rate') == null){
                return 0;
            }
            else{
                return $this->StoreReviews->avg('rate');
            }
        }
        else{
            if($this->UserReviews->avg('rate') == null){
                return 0;
            }
            else{
                return $this->UserReviews->avg('rate');
            }
        }

    }


}

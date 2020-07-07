<?php

namespace App\Http\Controllers;

use App\Coupons;
use App\OwnedCoupons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponsController extends Controller
{
    public function coupons(){
        $user = Auth::user();
        $coupons = Coupons::with('store')->get();
        $owned_coupons = OwnedCoupons::with('coupon')->with('coupon.store')->where('user_id',$user->id)->get();
        return response()->json(['coupons' => $coupons,'owned_coupons'=>$owned_coupons]);

    }
}

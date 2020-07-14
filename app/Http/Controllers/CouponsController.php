<?php

namespace App\Http\Controllers;

use App\Coupons;
use App\OwnedCoupons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UsedCoupons;
class CouponsController extends Controller
{
    public function coupons(Request $request){
        $user = Auth::user();
        $coupons = Coupons::with('store')->get();
        $owned_coupons = OwnedCoupons::with('coupon')->with('coupon.store')->where('user_id',$user->id)->get();
        return response()->json(['coupons' => $coupons,'owned_coupons'=>$owned_coupons]);

    }
    public function store_coupons(Request $request){
        $user = Auth::user();

        $owned_coupons = OwnedCoupons::with('coupon')->with('user')->whereHas('coupon', function  ($q) use ($user) {
            $q->where('store_id',$user->id);
        })->where('status',1)->get();
        return response()->json(['coupons'=>$owned_coupons]);

    }
    public function buy_coupon(Request $request){
        $user = Auth::user();
        $coupon = Coupons::find($request->id);
        if($user->points >= $coupon->price){
            $create = OwnedCoupons::create([
                'coupon_id'=>$coupon->id,
                'user_id'=>$user->id,
                'status'=>'0'
            ]);
            $user->update([
               'points'=>$user->points - $coupon->price
            ]);
            return response()->json(['status' => 'done']);
        }
        else{
            return response()->json(['status' => 'wrong','err'=>1],422);
        }
    }

    // err 1  -> not working coupon 2-> used before
    public function use_coupon(Request $request){
        $user = Auth::user();
        $coupon = OwnedCoupons::find($request->id);
        if($coupon){
            if($coupon->status == 0){
                $coupon->update([
                    "status"=>1
                ]);
                return response()->json(['status' => 'done']);
            }
            else{
                return response()->json(['status' => 'wrong','err'=>2],422);

            }

        }
        else{
            return response()->json(['status' => 'wrong','err'=>1],422);
        }


    }
}

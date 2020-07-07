<?php

namespace App\Http\Controllers;

use App\Reservations;
use App\SpecialEvents;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ReservationsController extends Controller
{

    // err 1 => baned from reserving any more 2-> already have an active reservation 3=> no available places
    public function reserve(Request $request){
        $user = Auth::user();
        $active_resrvations = $user->UserReservations->where('status','0');
        $ignored_resrvations = $user->UserReservations->where('status','2');

        $store= User::find($request->store_id);
        $type = $request->type;
        if($ignored_resrvations->count() >= 2){
            return response()->json(['status' => 'wrong','err'=>'1'],422);
        }
        else{
            if($active_resrvations->count() == 0){
                //here its reservation ok
                if($store->available >= 1){
                    if($type == 1){
                        //normal reservation
                        $create = Reservations::create([
                            'customer_id'=>$user->id,
                            'store_id'=>$store->id,
                            'type'=>1,
                            'status'=>0
                        ]);
                        $store->update([
                           'available'=>$store->available -1
                        ]);
                        return response()->json(['status' => 'done']);
                    }
                    else{
                        //special event one
                        $SpecialEvent = SpecialEvents::find($request->SpecialEvent_id);

                        $create = Reservations::create([
                            'customer_id'=>$user->id,
                            'store_id'=>$store->id,
                            'type'=>2,
                            'status'=>0,
                            'SpecialEvent_id'=>$SpecialEvent->id
                        ]);
                        $SpecialEvent->update([
                            'available'=>$SpecialEvent->available - 1
                        ]);
                        return response()->json(['status' => 'done']);
                    }
                }
                else{
                    return response()->json(['status' => 'wrong','err'=>'3'],422);
                }
            }
            else{
                return response()->json(['status' => 'wrong','err'=>'2'],422);
            }
        }
    }

    public function reservations(Request $request){
        $user = Auth::user();
        $comming = Reservations::with('store')->where(['customer_id'=>$user->id,'status'=>0])->get();
        $past = Reservations::with('store')->where(['customer_id'=>$user->id,'status'=>1])->get();
        return response()->json(['comming' => $comming,'past'=>$past]);

    }
}

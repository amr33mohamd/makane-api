<?php

namespace App\Http\Controllers;

use App\Reservations;
use App\Reviews;
use App\SpecialEvents;
use App\StoreReviews;
use App\User;
use Carbon\Carbon;
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
                if($type == 1){

                    if($store->available >= 1){
                        //normal reservation
                        $create = Reservations::create([
                            'customer_id'=>$user->id,
                            'store_id'=>$store->id,
                            'type'=>1,
                            'status'=>0,
                            'kids'=>$request->kids,
                            'time'=>Carbon::parse($request->time),
                            'persons'=>$request->persons,
                            'smoking'=>$request->smoking,
                            'outt'=>$request->outt
                        ]);
                        $store->update([
                           'available'=>$store->available -1
                        ]);
                        return response()->json(['status' => 'done']);
                    }
                    else{
                        return response()->json(['status' => 'wrong','err'=>'3'],422);
                    }

                }
                else{
                    //special event one
                    $SpecialEvent = SpecialEvents::find($request->SpecialEvent_id);
                    if($SpecialEvent->available >= 1) {

                        $create = Reservations::create([
                            'customer_id' => $user->id,
                            'store_id' => $store->id,
                            'type' => 2,
                            'status' => 0,
                            'SpecialEvent_id' => $SpecialEvent->id,
                            'time'=>$SpecialEvent->time
                        ]);
                        $SpecialEvent->update([
                            'available' => $SpecialEvent->available - 1
                        ]);
                        return response()->json(['status' => 'done']);
                    }
                    else{
                        return response()->json(['status' => 'wrong','err'=>'3'],422);
                    }

                }

            }
            else{
                return response()->json(['status' => 'wrong','err'=>'2'],422);
            }
        }
    }

    public function reservations(Request $request){
        $user = Auth::user();
        if($user->type == 1){
            $comming = Reservations::with('store')->with('user')->with('special_event')->where(['customer_id'=>$user->id,'status'=>0])->get();
            $whereData = array(array('customer_id',$user->id) , array('status' ,'!=','0'));

            $past = Reservations::with('store')->with('user')->with('special_event')->where($whereData)->get();

        }
        else{
            $comming = Reservations::with('store')->with('user')->with('special_event')->where(['store_id'=>$user->id,'status'=>0])->get();
            $whereData = array(array('store_id',$user->id) , array('status' ,'!=','0'));

            $past = Reservations::with('store')->with('user')->with('special_event')->where($whereData)->get();
        }
        return response()->json(['comming' => $comming,'past'=>$past]);

    }
    public function cancel(Request $request){
        $user = Auth::user();
        $reservation = Reservations::find($request->id);
        $reservation->update([
          'status'=>3
        ]);
        $store = $reservation->store;
        if($reservation->type == 1) {
            $reservation->store->update([
                'available' => $store->available + 1
            ]);
        }
        else{
            $reservation->special_event->update([
                'available' => $reservation->special_event->available + 1
            ]);
        }
        return response()->json(['status' => 'done']);

    }
    public function notArrived(Request $request){
        $user = Auth::user();
        $reservation = Reservations::find($request->id);
        $reservation->update([
            'status'=>2
        ]);

        return response()->json(['status' => 'done']);

    }
    public function arrived(Request $request){
        $user = Auth::user();
        $reservation = Reservations::find($request->id);
        $reservation->update([
            'status'=>1
        ]);

        return response()->json(['status' => 'done']);

    }

    public function rate(Request $request){
        $user = Auth::user();
        if($user->type == 1){
            $review = Reviews::create([
                'store_id'=>$request->store_id,
                'reservation_id'=>$request->reservation_id,
                'review'=>$request->review,
                'rate'=>$request->stars,
                'reviewer_id'=>$user->id
            ]);
        }
        else{
            $review = StoreReviews::create([
                'user_id'=>$request->user_id,
                'reservation_id'=>$request->reservation_id,
                'review'=>$request->review,
                'rate'=>$request->stars,
                'reviewer_id'=>$user->id
            ]);
        }

        return response()->json(['status' => 'done']);
    }
}

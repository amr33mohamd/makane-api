<?php

namespace App\Http\Controllers;

use App\Settings;
use App\User;
use Illuminate\Http\Request;
use DB;

class StoreController extends Controller
{
    public function stores(Request $request){
        $search = $request->search;
        if($request->lat && $request->lng){
            $resturants = User::query()->where('type','2')->with('SpecialEvents')->with('StoreImages')->
            orderBy(DB::raw("3959 * acos( cos( radians({$request->input('lat')}) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(-{$request->input('lng')}) ) + sin( radians({$request->input('lat')}) ) * sin(radians(lat)) )"), 'ASC')->with('StoreReviews.reviewer')->when(isset($search) && $search != '',
                function ($query) use ($search) {
                    return $query->where('name','like', "%".$search."%");
                })->get();
            $cafes = User::query()->where('type','3')->with('SpecialEvents')->with('StoreImages')->
            orderBy(DB::raw("3959 * acos( cos( radians({$request->input('lat')}) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(-{$request->input('lng')}) ) + sin( radians({$request->input('lat')}) ) * sin(radians(lat)) )"), 'ASC')->with('StoreReviews.reviewer')->
            when(isset($search) && $search != '',
                function ($query) use ($search) {
                    return $query->where('name','like', "%".$search."%");
                })
                ->get();
        }
        else{
            $cafes = User::query()->where('type','3')->with('StoreReviews.reviewer')
                ->with('StoreImages')->when(isset($search) && $search != '',
                    function ($query) use ($search) {
                        return $query->where('name','like', "%".$search."%");
                    })->with('SpecialEvents')->get();
            $resturants = User::query()->where('type','2')->when(isset($search) && $search != '',
                function ($query) use ($search) {
                    return $query->where('name','like', "%".$search."%");
                })->with('StoreReviews.reviewer')
                ->with('StoreImages')->with('SpecialEvents')->get();

        }

        return response()->json(['restaurants' => $resturants,'cafes'=>$cafes]);
    }
    public function store(Request $request){
        $store = User::where([
            'id'=>$request->id ,
        ])->with('StoreReviews.reviewer')->get();
        return response()->json(['store' => $store]);

    }

    public function settings(Request $request){
        $settings = Settings::all();
        return response()->json(['settings' => $settings]);

    }
}

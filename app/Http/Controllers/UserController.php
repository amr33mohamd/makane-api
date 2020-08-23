<?php

namespace App\Http\Controllers;

use App\Images;
use App\Invitations;
use App\Settings;
use App\SpecialEvents;
use App\User;
use Twilio\Rest\Client as Client;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

    public function SignUp(Request $request){

        $validatedDat = $request->validate([

            'name' => 'required|max:255',
            'email' => 'required|unique:users',
            'password'=> 'required|min:6',
            'code'=>'nullable',
            'country'=>'required',
            'phone'=>'required|min:6|unique:users']
        );
            $validatedData = \Validator::make(request()->all(), [

            'name' => 'required|max:255',
            'email' => 'required|unique:users',
            'password'=> 'required|min:6',
            'code'=>'nullable',
            'country'=>'required',
            'phone'=>'required|min:6|unique:users']
        );


        if($request->code){
            $inviter = User::where('invite_code',$request->code);
            if($inviter->count() > 0){
                $create = User::create([
                    "name"=>$request->name,
                    "email"=>$request->email,
                    "verify_code"=>rand(1000,10000),
                    "country"=>$request->country,
                    "phone"=>$request->phone,
                    "password"=>$request->password,
                    "invite_code"=>rand(1000,10000),
                ]);
                User::find($create->id)->update([
                    'invited_code'=>$request->code
                ]);

            }
            else{
                $validatedData->errors()->add('code', 'code is wrong');
                return response()->json(['errors'=>$validatedData->errors()->getMessages(),'status'=>'wrong'], 422);

            }
        }
        else{
            $verify_cod = rand(1000,10000);
            $account_sid = getenv("TWILIO_SID");
            $auth_token = getenv("TWILIO_AUTH_TOKEN");
            $twilio_number = getenv("TWILIO_NUMBER");
            $client = new Client($account_sid, $auth_token);
//            $client->messages->create('+'.$request->phone,
//                ['from' => $twilio_number, 'body' => 'your verification code is '.$verify_cod] );

            $create = User::create([
                "name"=>$request->name,
                "email"=>$request->email,
                "verify_code"=>rand(1000,10000),
                "country"=>$request->country,
                "phone"=>$request->phone,
                "password"=>$request->password,
                "invite_code"=>$verify_cod,
            ]);
        }
        $credentials = request(['email', 'password']);

         $token = auth()->attempt($credentials);
            return response()->json(['status' => 'done','user'=>$create,'token'=>$token]);


    }
    public function add_phone(Request $request){
        $user = Auth::user();
        $validatedDat = $request->validate([
            'code'=>'nullable',
            'phone'=>'required|min:6|unique:users']

            );
            $validatedData = \Validator::make(request()->all(), [
                'code'=>'nullable',
                'phone'=>'required|min:6|unique:users']
        );

        if($request->code) {
            $inviter = User::where('invite_code',$request->code);
            if($inviter->count() > 0){
                $user->update([
                    'invited_code'=>$request->code,
                    'phone'=>$request->phone,
                    'verified'=>1
                ]);
                    $invitaion = Invitations::create([
                        "invited_id"=>$user->id,
                        "inviter_id"=>$inviter->first()->id
                    ]);
                    $invite_points = Settings::where('attr','invite_points')->first()->value;
                    $user->update([
                        'points'=>$invite_points
                    ]);
                    $inviter->first()->update([
                        'points'=>$inviter->first()->points + $invite_points
                    ]);
                $user->update([
                    'verified'=>1
                ]);
                return response()->json(['status' => 'done']);

            }
            else{
                $validatedData->errors()->add('code', 'code is wrong');
                return response()->json(['errors'=>$validatedData->errors()->getMessages(),'status'=>'wrong'], 422);

            }

            }




        else{
            $user->update([
                'phone'=>$request->phone,
                'verified'=>1

            ]);

            return response()->json(['status'=>'done'] );

        }
    }
    public function verify(Request $request){
        $validatedData = $request->validate([
            'code'=>'required'
        ]);

        $user = Auth::user();
        $inviter = User::where('invite_code',$user->invited_code);

        if($user->verify_code == $request->code){

            if($inviter->count() > 0){
                $invitaion = Invitations::create([
                    "invited_id"=>$request->id,
                    "inviter_id"=>$inviter->first()->id
                ]);
            $invite_points = Settings::where('attr','invite_points')->first()->value;
            $user->update([
               'points'=>$invite_points
            ]);
            $inviter->first()->update([
                'points'=>$inviter->first()->points + $invite_points
            ]);
            }
            $user->update([
                'verified'=>1
            ]);

            return response()->json(['status' => 'done']);

        }
        else{
            return response()->json(['status' => 'wrong'],422);
        }
    }
    public function login(Request $request){
        $credentials = request(['email', 'password']);

        if(!$token = auth()->attempt($credentials)){
            return response()->json(['status' => 'wrong'],422);

        }
        else{
            $user = Auth::user();
            return response()->json(['status' => 'done','user'=>$user,'token'=>$token]);
        }

    }

    public function social_login(Request $request){
//        $credentials = request(['email', 'password']);

        if(!$token = Auth::attempt(['email' => $request->email, 'password' => 'Amr33304454'])){
            $create = User::create([
                "name"=>$request->email,
                "email"=>$request->email,
                "verify_code"=>rand(1000,10000),
                "country"=>$request->email,
                "phone"=>0,
                "password"=>'Amr33304454',
                "invite_code"=>rand(1000,10000),
            ]);
            $token = Auth::attempt(['email' => $request->email, 'password' => 'Amr33304454']);


            return response()->json(['token' => $token,'status'=>2]);

        }
        else{
            $user = Auth::user();
            if($user->phone == 0){
                return response()->json(['token' => $token,'status'=>2]);
            }
            else{
                return response()->json(['status' => '1','user'=>$user,'token'=>$token]);
            }
        }
    }

    public function user(Request $request){
        $user = Auth::user();
        $data = User::where('id',$user->id)->with('StoreImages')->with('SpecialEvents')->first();
        return response()->json(['user'=>$data]);
    }
    public function update_user(Request $request)
    {
        $validatedData = $request->validate([
            'name'=>'required',
            'email' => 'nullable|unique:users',
            'password'=> 'nullable|min:6',

        ]);
        $user = Auth::user();
        $user->update(array_filter($request->all()));
        return response()->json(['status' => 'done']);

    }
    public function uploadImage(Request $request){
        if(!$request->file('image'))
            return ['status' => false,'errors' => 'image is required'];

        $image = $request->file('image');
        $imageName = time().rand(1000,9999).'.'.$image->getClientOriginalExtension();
        $image->move('images',$imageName);

        return ['status' => true,'message'=>trans('api.upload.file.success'),'file_name'=>$imageName];

    }
    public function addImage(Request $request){
        if(!$request->file('image'))
            return ['status' => false,'errors' => 'image is required'];

        $image = $request->file('image');
        $imageName = time().rand(1000,9999).'.'.$image->getClientOriginalExtension();
        $image->move('images',$imageName);
        $user = Auth::user();
        Images::create([
           'image'=>$imageName,
           'user_id'=>$user->id
        ]);
        return ['status' => true];
    }

    public function deleteImage(Request $request)
    {
        Images::find($request->id)->delete();
        return ['status' => true];

    }
    public function addEvent(Request $request){
        $user = Auth::user();
        SpecialEvents::create([
           'name'=>$request->name,
           'time'=>$request->time,
            'store_id'=>$user->id,
            'available'=>$request->available,
            'description'=>'k'
        ]);
        return ['status'=>true];
    }
    public function deleteEvent(Request $request)
    {
        SpecialEvents::find($request->id)->delete();
        return ['status' => true];

    }
    public function updateEvent(Request $request)
    {

        $event = SpecialEvents::find($request->id);
        $event->update(array_filter($request->all()));
        return response()->json(['status' => 'done']);
    }
//    public function test(Request $request){
//
//    }

    }

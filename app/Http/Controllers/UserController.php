<?php

namespace App\Http\Controllers;

use App\Invitations;
use App\Settings;
use App\User;
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
        $validatedData = Validator::make(
            $request->all(),
            array(
            'name' => 'required|max:255',
            'email' => 'required|unique:users',
            'password'=> 'required|min:6',
            'code'=>'nullable',
            'country'=>'required',
            'phone'=>'required|min:6|unique:users')
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
            $create = User::create([
                "name"=>$request->name,
                "email"=>$request->email,
                "verify_code"=>rand(1000,10000),
                "country"=>$request->country,
                "phone"=>$request->phone,
                "password"=>$request->password,
                "invite_code"=>rand(1000,10000),
            ]);
        }
        $credentials = request(['email', 'password']);

         $token = auth()->attempt($credentials);
            return response()->json(['status' => 'done','user'=>$create,'token'=>$token]);


    }
    public function verify(Request $request){
        $validatedData = $request->validate([
            'code'=>'required',
            'id'=>'required'
        ]);

        $user = User::where('id',$request->id)->first();
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
    public function user(Request $request){
        $user = Auth::user();
        return response()->json(['user'=>$user]);
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
    }

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//stores routes ----------->
Route::get('/stores','StoreController@stores');
Route::get('/store','StoreController@store');


//reserve
Route::post('/reserve','ReservationsController@reserve');
Route::post('/reservations','ReservationsController@reservations');


Route::post('/coupons','CouponsController@coupons');


//auth routes ----------->
Route::get('/signup','UserController@SignUp');
Route::get('/verify','UserController@verify');
Route::get('/login','UserController@login');




Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

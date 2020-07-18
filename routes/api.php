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
Route::post('/cancel_reservation','ReservationsController@cancel');
Route::post('/user-review','ReservationsController@rate');
Route::post('/not-arrived','ReservationsController@notArrived');
Route::post('/arrived','ReservationsController@arrived');



//coupons
Route::post('/coupons','CouponsController@coupons');
Route::post('/store-coupons','CouponsController@store_coupons');
Route::post('/use-coupon','CouponsController@use_coupon');
Route::post('/buy_coupon','CouponsController@buy_coupon');


//pay routes
Route::get('/pay-month','PayController@pay_month');
Route::post('/pay-month-response','PayController@pay_month_response');




//auth routes ----------->
Route::get('/signup','UserController@SignUp');
Route::get('/test','UserController@test');

Route::post('/verify','UserController@verify');
Route::post('/login','UserController@login');
Route::post('/user','UserController@user');
Route::post('/update_user','UserController@update_user');
Route::post('/upload-image','UserController@uploadImage');
Route::post('/add-image','UserController@addImage');
Route::post('/delete-image','UserController@deleteImage');
Route::post('/add-event','UserController@addEvent');
Route::post('/delete-event','UserController@deleteEvent');
Route::post('/edit-event','UserController@editEvent');



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
  $SERVER_API_KEY = 'AAAATFeH_L4:APA91bGUvk9ZFani7HODMs1diRh_r-5XpEFyScZpYlb3wNjhvD3ujmta4Rgl_oKuzCIr9qlY0nDo1T0l5Ria4iluTPedVdPn2jHJhEjNHUARnO7whnS8UIRqT7lLRkuG2BCfJTG__BSm';

  $data = [
    "to"=>"/topics/131",
      "notification" => [
          "title" => 'lll',
          "body" =>'kkkk',
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
  return $response;
    // return view('welcome');
});

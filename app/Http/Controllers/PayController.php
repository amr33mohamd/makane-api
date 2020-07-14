<?php

namespace App\Http\Controllers;

use App\Settings;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PayController extends Controller
{
    public function pay_month(Request $request){
        $user_id = $request->id;
        $user = User::find($user_id);
        $client = new Client();

        $responseAuth = $client->request('post', 'https://accept.paymobsolutions.com/api/auth/tokens', [
            'json' => [
                'api_key' => 'ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SndjbTltYVd4bFgzQnJJam94TkRreE1Td2libUZ0WlNJNkltbHVhWFJwWVd3aUxDSmpiR0Z6Y3lJNklrMWxjbU5vWVc1MEluMC5sQzBJVDJTLVhaTlkwazV5cllBRDNuVmpKalZxaWRxNXFrREtJMkhHcGJHYlZBMkc1aUhpWVEtVDZGZ0FtOWthRXprV21heDFuQUZtVFl5YVBEOGgxQQ=='
            ]
        ]);
        $marchentData = json_decode($responseAuth->getBody(), true);

// return $marchentData;
        $responseOrder = $client->request('post', 'https://accept.paymobsolutions.com/api/ecommerce/orders', [
            'json' => [
                "auth_token" => $marchentData['token'], // auth token obtained from step1
                "delivery_needed" => "false",
                "merchant_id" => $marchentData['profile']['user']['id'],      // merchant_id obtained from step 1
                "amount_cents" =>(Settings::query()->where('attr','renew_price')->first()->value ) * 100,
                "currency" => "EGP",
                "merchant_order_id" =>  rand()
            ]
        ]);

        $orderData = json_decode($responseOrder->getBody(), true);

//return $orderData;
        $responseLastOrder = $client->request('post', 'https://accept.paymobsolutions.com/api/acceptance/payment_keys', [
            'json' => [
                "auth_token" => $marchentData['token'], // auth token obtained from step1
                "delivery_needed" => "false",
                "order_id" => $orderData['id'],
                "expiration" => 3600,
                "integration_id" => 26021,  // card integration_id will be provided upon signing up,
                "billing_data" => [
                    "apartment"=> "404",
                    "email" => "claudette09@exa.com",
                    "floor" => $user_id,
                    "first_name" => "amrm",
                    "street" => "reservation",
                    "building" => "8028",
                    "phone_number" => "+86(8)9135210487",
                    "shipping_method" => "PKG",
                    "postal_code" => "01898",
                    "city" => "Jaskolskiburgh",
                    "country" => "CR",
                    "last_name" => "Nicolas",
                    "state" => "Utah"
                ],
                "merchant_id" => $marchentData['profile']['user']['id'],      // merchant_id obtained from step 1
                "amount_cents" => (Settings::query()->where('attr','renew_price')->first()->value ) * 100,
                "currency" => "EGP",
            ]
        ]);

        $lastorderData = json_decode($responseLastOrder->getBody(), true);
        return Redirect::to('https://accept.paymobsolutions.com/api/acceptance/iframes/43131?payment_token=' . $lastorderData['token']);

    }

    public function pay_month_response(Request $request){
        $status = $request->json()->all()['obj']['success'];
        $user_id = $request->json()->all()['obj']['order']['shipping_data']['floor'];
        if($status == true){
            $user = User::find($user_id);
            $user->upadte([
                'renew_date'=>Carbon::now()->addMonths(1)
            ]);
        }


    }
}

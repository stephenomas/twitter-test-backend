<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TwitterController extends Controller
{
    public function authenticate (Request $request){
        $response = Http::asForm()->withBasicAuth($request->client_id, $request->secret)->post('https://api.twitter.com/2/oauth2/token',
        [ 'grant_type' =>  'authorization_code',
            'code' => $request->code,
            'client_id' => $request->client_id,
            'redirect_uri'=>'http://localhost:3000/verify',
            "code_verifier" => 'challenge'
        ]
    );


    return response()->json($response->json(), 201);

    }

    public function send_message(Request $request){
        $user_data = Http::withToken($request->token)->get('https://api.twitter.com/2/users/me');

        if($user_data->successful()){
            $message = Http::withToken($request->token)->post('https://api.twitter.com/2/direct_messages/events/new',
        [
            ["event" =>
                ["type" => "message_create",
                "message_create" =>
                    ["target" =>["recipient_id" => $user_data->json()['data']['id']],
                    "message_data" => ["text" => "Your OTP is {$request->otp}"]
                    ]
                ]
            ]

        ]
        );
         return response()->json( $user_data->json()['data']['id'], 201);
        }else{
            return response()->json( 'not sent', 201);
        }

    }
}



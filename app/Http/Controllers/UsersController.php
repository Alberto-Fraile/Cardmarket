<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;
use App\Models\User;
use App\Models\Card;
use App\Models\Collection;

class UsersController extends Controller
{
    public function register(Request $req){

        $response = ["status" => 1, "msg" => ""];

        $validator = validator::make(json_decode($req->getContent(),true
        ), 
            ['name' => 'required|max:30',
             'email' => 'required|email|unique:App\Models\User,email|max:30',
             'password' => 'required|regex:/(?=.*[a-z)(?=.*[A-Z])(?=.*[0-9]).{6,}/',
             'rol' => 'required|in:particular,professional,admin'
            ]
        );

        if ($validator->fails()){
            $response['status'] = 0;
            $response['msg'] = $validator->errors();

        }else {
            $datos = $req->getContent();
            $datos = json_decode($datos);
            $user = new User();

            $user->name = $datos->name;
            $user->email = $datos->email;
            $user->password = Hash::make($datos->password);
            $user->rol = $datos->rol;

            try{
                $user->save();
                $response['msg'] = "User register with id ".$user->id;
            }catch(\Exception $e){
                $response['status'] = 0;
                $response['msg'] = "An error has occurred: ".$e->getMessage();
            }
        }
        return response()->json($response);
    }

    public function login(Request $req){
        $response = ["status" => 1, "msg" => ""];

        $datos = $req->getContent();
        $datos = json_decode($datos);
        
        $name = $datos->name;

        $user = User::where('name', '=', $name)->first();

        if($user){
            if (Hash::check($datos->password, $user->password)) {
                do{
                    $apitoken = Hash::make($user->id.now());

                }while(User::where('api_token', $apitoken)->first());

                $user->api_token = $apitoken;
                $user->save();
                $response['msg'] = "Correct login ".$user->api_token;

            }else {
                $response['status'] = 0;
                $response['msg'] = "An error has occurred: ";      
            }
        }else{
            $response['status'] = 0;
            $response['msg'] = "An error has occurred: ";  
        }
        return response()->json($response);
    }

    public function recoveredPassword(Request $req){ 
        $response = ["status" => 1, "msg" => ""];

        $datos = $req->getContent();
        $datos = json_decode($datos);

        $email = $datos->email;
        
        if($user = User::where('email', '=', $datos->email)->first()){

            $user->api_token = null;

                $password = "aAbBcCdDeEfFgGhHiIjJkKlLmMnNñÑoOpPqQrRsStTuUvVwWxXyYzZ0123456789";
                $passwordCharCount = strlen($password);
                $passwordLength = 8;
                $newPassword = "";
                for($i=0;$i<$passwordLength;$i++) {
                $newPassword .= $password[rand(0,$passwordCharCount-1)];
                }
            
            $user->password = $newPassword;
            $response['msg'] = "Here is the new generated password: ".$user->password;
            $user->password = Hash::make($newPassword);
            $user->save();  

        }else{
            $response['status'] = 0;
            $response['msg'] = "An error has occurred: ";
        } 
        return response()->json($response);
    }

    public function asociate_cards($cards_id, $collections_id){
        $response = ["status" => 1, "msg" => ""];

        $card = Card::find($cards_id);
        $collection = Collection::find($collections_id);

        try{
            if ($card && $collection){
                $card->collections()->attach($collection);
                $response['msg'] = "Card asociate with collection id ".$collection->id;
            }else {
                $response["msg"] = "User not found";
                $response["status"] = 0;
            }

        }catch(\Exception $e){
            $response['status'] = 0;
            $response['msg'] = "An error has occurred: ".$e->getMessage();
        }

        return response()->json($response);
    }

}



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

        $respuesta = ["status" => 1, "msg" => ""];

        $validator = validator::make(json_decode($req->getContent(),true
        ), 
            ['name' => 'required|max:30',
             'email' => 'required|email|unique:App\Models\User,email|max:30',
             'password' => 'required|regex:/(?=.*[a-z)(?=.*[A-Z])(?=.*[0-9]).{6,}/',
             'rol' => 'required|in:particular,profesional,administrador'
            ]
        );

        if ($validator->fails()){
            $respuesta['status'] = 0;
            $respuesta['msg'] = $validator->errors();

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
                $respuesta['msg'] = "User register with id ".$user->id;
            }catch(\Exception $e){
                $respuesta['status'] = 0;
                $respuesta['msg'] = "Se ha producido un error: ".$e->getMessage();
            }
        }
        return response()->json($respuesta);
    }

    public function login(Request $req){
        $respuesta = ["status" => 1, "msg" => ""];

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
                $respuesta['msg'] = "Login correcto ".$user->api_token;

            }else {
                $respuesta['status'] = 0;
                $respuesta['msg'] = "Se ha producido un error: ";      
            }
        }else{
            $respuesta['status'] = 0;
            $respuesta['msg'] = "Se ha producido un error: ";  
        }
        return response()->json($respuesta);
    }

    public function recoveredPassword(Request $req){ 
        $respuesta = ["status" => 1, "msg" => ""];

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
            $respuesta['msg'] = "Aquí tiene la contraseña nueva generada: ".$user->password;
            $user->password = Hash::make($newPassword);
            $user->save();  

        }else{
            $respuesta['status'] = 0;
            $respuesta['msg'] = "Se ha producido un error: ";
        } 
        return response()->json($respuesta);
    }

    public function asociate_cards($cards_id, $collections_id){
        $respuesta = ["status" => 1, "msg" => ""];

        $card = Card::find($cards_id);
        $collection = Collection::find($collections_id);

        try{
            if ($card && $collection){
                $card->collections()->attach($collection);
                $respuesta['msg'] = "Card asociate with collection id ".$collection->id;
            }else {
                $respuesta["msg"] = "Usuario no encontrado";
                $respuesta["status"] = 0;
            }

        }catch(\Exception $e){
            $respuesta['status'] = 0;
            $respuesta['msg'] = "Se ha producido un error: ".$e->getMessage();
        }

        return response()->json($respuesta);
    }

}



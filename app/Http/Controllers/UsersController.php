<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersController extends Controller
{
    public function register(Request $req){

        $respuesta = ["status" => 1, "msg" => ""];

        $validator = validator::make(json_decode($req->getContent(),true
        ), 
            ['name' => 'required|name|unique:App\Models\User,name|max:30',
             'email' => 'required|email|unique:App\Models\User,email|max:30',
             'password' => 'required|regex:/(?=.*[a-z)(?=.*[A-Z])(?=.*[0-9]).{6,}/',
             'rol' => 'required|in:particular,profesional,administrador'
            ]);

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
                $respuesta['msg'] = "Usuario guardado con id ".$user->id;
            }catch(\Exception $e){
                $respuesta['status'] = 0;
                $respuesta['msg'] = "Se ha producido un error: ".$e->getMessage();
            }
        }

    return response()->json($respuesta);
}

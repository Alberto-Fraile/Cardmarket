<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CardSold;
use App\Models\User;
use App\Models\Card;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

class CardsSoldsController extends Controller
{
    public function createCardsSolds(Request $req){

        $response = ["status" => 1, "msg" => ""];

        $validator = validator::make(json_decode($req->getContent(),true
        ), 
            ['name' => 'required|max:30',
             'amount' => 'required|max:30',
             'price' => 'required|max:30'
            ]
        );

        if ($validator->fails()){
            $response['status'] = 0;
            $response['msg'] = $validator->errors();

        }else {
            $datos = $req->getContent();
            $datos = json_decode($datos);

            $card = new CardSold();

            $card->name = $datos->name;
            $card->amount = $datos->amount;
            $card->price = $datos->price;

            try{
                $card->save();
                $response['msg'] = "Card save with id ".$card->id;
            }catch(\Exception $e){
                $response['status'] = 0;
                $response['msg'] = "An error has occurred: ".$e->getMessage();
            }
        }
        return response()->json($response);
    }

    public function searchBuyCard(Request $req){
        $response = ["status" => 1, "msg" => ""];
        $datos = $req->getContent();
        
        $datos = json_decode($datos);

        try{
            //$card = $card->makeHidden(['id','created_at','updated_at']);
            $card = DB::Table('card_solds')
            ->where('name', 'like', '%' .$req->input('name'). '%')
            ->select('card_solds.*', 'users.name')
            ->orderBy('card_solds.price', 'asc')
            ->get();
            $response['datos'] = $card; 

        }catch(\Exception $e){
           $response['status'] = 0;
           $response['msg'] = "An error has occurred: ".$e->getMessage();           
        }
        return response()->json($response);
    }

}

            // if(isset($datos->user_asociate) && !empty($datos->user_asociate) && isset($datos->card_asociate) && !empty($datos->card_asociate) ){
            //     $user = User::find($datos->user_asociate);
            //     $card = Carta::find($datos->card_asociate);
            //     if($user && $card){
            //         $card -> user_asociate = $datos->user_asociate;
            //         $card -> card_asociate = $datos->card_asociate;
            //         try {
            //             $card->save();
            //             $respuesta["msg"] = "Venta subida correctamente";
            //         }catch (\Exception $e) {
            //             $respuesta["status"] = 0;
            //             $respuesta["msg"] = "Se ha producido un error".$e->getMessage();  
            //         }  
            //     } else {
            //         $respuesta["status"] = 0;
            //         $respuesta["msg"] = "Usuario o carta no encontrada";
            //     }
            // }
            // else {
            //     $respuesta["status"] = 0;
            //     $respuesta["msg"] = "No se ha asociado ningun usuario o carta, vuelve a intentarlo";
            // }
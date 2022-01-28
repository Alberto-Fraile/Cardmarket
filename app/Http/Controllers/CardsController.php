<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Card;
use Illuminate\Support\MessageBag;

class CardsController extends Controller
{
    public function createCard(Request $req){

        $respuesta = ["status" => 1, "msg" => ""];

        $datos = $req->getContent();
        $datos = json_decode($datos);

        $card = new Card();

        $card->name = $datos->name;
        $card->description = $datos->description;
        $card->collections_id = $datos->collections_id;

        try{
            $card->save();
            $respuesta['msg'] = "Card save with id ".$card->id;
        }catch(\Exception $e){
            $respuesta['status'] = 0;
            $respuesta['msg'] = "Se ha producido un error: ".$e->getMessage();
        }

        return response()->json($respuesta);
    }

    public function searchCard(Request $req){
        $respuesta = ["status" => 1, "msg" => ""];
        $datos = $req->getContent();
        
        $datos = json_decode($datos);
        
        try{
            $card = DB::Table('card');

            if ($req ->has('name')) {
               $card = Card::withCount('card as Cantidad')
              ->where('name', 'like', '%' .$req->input('name'). '%')
                ->get();
                $respuesta['datos'] = $card;
            }else{
                $card = Card::withCount('card as Cantidad')->get(); 
                $respuesta['datos'] = $card;
            }        
        }catch(\Exception $e){
           $respuesta['status'] = 0;
           $respuesta['msg'] = "Se ha producido un error: ".$e->getMessage();           
        }
        return response()->json($respuesta);
    }
}

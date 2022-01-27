<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $card->collection = $datos->collection;

        try{
            $card->save();
            $respuesta['msg'] = "Card save with id ".$card->id;
        }catch(\Exception $e){
            $respuesta['status'] = 0;
            $respuesta['msg'] = "Se ha producido un error: ".$e->getMessage();
        }

        return response()->json($respuesta);
    }
}

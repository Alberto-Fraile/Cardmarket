<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CardSold;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

class CardsSoldController extends Controller
{
    public function createCardSold(Request $req){

        $response = ["status" => 1, "msg" => ""];
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

        return response()->json($response);
    }

}

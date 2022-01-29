<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CardSold;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

class CardsSoldsController extends Controller
{
    public function createCardsSolds(Request $req){

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

    public function searchBuyCard(Request $req){
        $response = ["status" => 1, "msg" => ""];
        $datos = $req->getContent();
        
        $datos = json_decode($datos);

        try{
            //$card = $card->makeHidden(['id','created_at','updated_at']);
            $card = DB::Table('card_solds')
            ->where('name', 'like', '%' .$req->input('name'). '%')
            ->get();
            $response['datos'] = $card; 

        }catch(\Exception $e){
           $response['status'] = 0;
           $response['msg'] = "An error has occurred: ".$e->getMessage();           
        }
        return response()->json($response);
    }

}

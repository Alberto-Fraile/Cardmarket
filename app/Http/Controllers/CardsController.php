<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Card;
use Illuminate\Support\MessageBag;

class CardsController extends Controller
{
    public function createCard(Request $req){

        $response = ["status" => 1, "msg" => ""];

        $datos = $req->getContent();
        $datos = json_decode($datos);

        $card = new Card();

        $card->name = $datos->name;
        $card->description = $datos->description;
        $card->collections_id = $datos->collections_id;

        try{
            $card->save();
            $response['msg'] = "Card save with id ".$card->id;
        }catch(\Exception $e){
            $response['status'] = 0;
            $response['msg'] = "An error has occurred: ".$e->getMessage();
        }

        return response()->json($response);
    }

    public function searchCard(Request $req){
        $response = ["status" => 1, "msg" => ""];
        $datos = $req->getContent();
        
        $datos = json_decode($datos);
        
        try{
            $card = DB::Table('card');

            if ($req ->has('name')) {
                $card = Card::withCount('collections as Amount')
                ->where('name', 'like', '%' .$req->input('name'). '%')
                ->get();
                $response['datos'] = $card;
            }else{
                $card = Card::withCount('collections as Amount')->get(); 
                $response['datos'] = $card;
            }        
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
            $card = DB::Table('card');

            if ($req ->has('name')) {
                $card = Card::withCount('collections as Amount')
                ->where('name', 'like', '%' .$req->input('name'). '%')
                ->get();
                $response['datos'] = $card;
            }else{
                $card = Card::withCount('collections as Amount')->get(); 
                $response['datos'] = $card;
            }        
        }catch(\Exception $e){
           $response['status'] = 0;
           $response['msg'] = "An error has occurred: ".$e->getMessage();           
        }
        return response()->json($response);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use App\Models\Card;
use App\Models\Collection;
use App\Models\CardCollection;
use App\Models\CardSold;
use App\Models\User;

class CardsController extends Controller
{
    public function createCard(Request $req){

        $response = ["status" => 200, "msg" => ""];
        
        $validator = validator::make(json_decode($req->getContent(),true
        ), 
            [
              'name' => 'required|max:30',
              'description' => 'required|max:30'
            ]
        );

        if ($validator->fails()){
            $response['status'] = 402;
            $response['msg'] = $validator->errors();

        }else {
            $datos = $req->getContent();
            $datos = json_decode($datos);

            $collection = Collection::where('name', '=',$datos->collection)->first();
            if ($collection) {
                $card = new Card();
                $card->name = $datos->name;
                $card->description = $datos->description;

                try {
                    $card->save();
                    $cardCollection = new CardCollection();
                    $cardCollection->cards_id = $card->id;
                    $cardCollection->collections_id = $collection->id;
                    $cardCollection->save();
                    $response['msg'] = 'Carta guardada y asociada con la colección';
                } catch (\Exception $e) {
                    $response['status'] = 400;
                    $response['msg'] = 'Se ha producido un error: '.$e->getMessage();
                }
            } else {
                $response['status'] = 401;
                $response['msg'] = 'La coleccion ingresada no existe';
            }
        }
        return response()->json($response);
    }

    public function createCollection(Request $req){

        $response = ["status" => 200, "msg" => ""];

        $validator = validator::make(json_decode($req->getContent(),true
        ), 
            ['name' => 'required|max:30',
             'symbol' => 'required|max:30',
             'date' => 'required|max:30',
             //'cards' => 'required'
            ]
        );

        if ($validator->fails()){
            $response['status'] = 400;
            $response['msg'] = $validator->errors();

        }else {
            $datos = $req->getContent();
            $datos = json_decode($datos);

            $collection = new Collection();

            $collection->name = $datos->name;
            $collection->symbol = $datos->symbol;
            $collection->date = $datos->date;

            try{
                $collection->save();
                $response['msg'] = "Collection save with id ".$collection->id;
            }catch(\Exception $e){
                $response['status'] = 400;
                $response['msg'] = "An error has occurred: ".$e->getMessage();
            }
        }
        return response()->json($response);
    }

    public function createCardsSolds(Request $req){

        $response = ["status" => 200, "msg" => ""];

        $validator = validator::make(json_decode($req->getContent(),true
        ), 
            [
            //'name' => 'required|max:30',
             'amount' => 'required|max:30',
             'price' => 'required|max:30'
            ]
        );

        if ($validator->fails()){
            $response['status'] = 400;
            $response['msg'] = $validator->errors();

        }else {
            $datos = $req->getContent();
            $datos = json_decode($datos);

            $card = Card::where('name', '=',$datos->card)->first();
            if ($card) {
                $cardSold = new CardSold();
                //$card->name = $datos->name;
                $cardSold->amount = $datos->amount;
                $cardSold->price = $datos->price;

                try {
                    $cardSold->save();
                    $cardSold = new CardSold();
                    $cardSold->card_asociate = $card->id;
                    $cardSold->user_asociate = $user->id;
                    $cardSold->save();
                    $response['msg'] = 'Carta guardada y asociada con el usuario';
                } catch (\Exception $e) {
                    $response['status'] = 400;
                    $response['msg'] = 'Se ha producido un error: '.$e->getMessage();
                }
            } else {
                $response['status'] = 400;
                $response['msg'] = 'La carta ingresada no existe';
            }
        }
        return response()->json($response);
    }

    public function searchCard(Request $req){
        $response = ["status" => 200, "msg" => ""];
        $datos = $req->getContent();
        
        $datos = json_decode($datos);
        
        try{
            $card = DB::Table('card');

            Log::info('Show table card ');

            if ($req ->has('name')) {
                $card = Card::withCount('collections as Amount')
                ->where('name', 'like', '%' .$req->input('name'). '%')
                ->get();
                $response['datos'] = $card;
                Log::debug($response);
            }else{
               $response['status'] = 400;
               $response['msg'] = "An error has occurred: "; 
               Log::error('An error has occurred: ');
            }        
        }catch(\Exception $e){
           $response['status'] = 400;
           $response['msg'] = "An error has occurred: ".$e->getMessage(); 
           Log::error('An error has occurred: ');          
        }
        return response()->json($response);
    }    

    public function searchBuyCard(Request $req){
        $response = ["status" => 200, "msg" => ""];
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
           $response['status'] = 400;
           $response['msg'] = "An error has occurred: ".$e->getMessage();           
        }
        return response()->json($response);
    }

}
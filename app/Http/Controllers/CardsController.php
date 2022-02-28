<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Card;
use App\Models\Collection;
use App\Models\CardSold;
use App\Models\User;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;

class CardsController extends Controller
{
    public function createCard(Request $req){

        $response = ["status" => 1, "msg" => ""];
        
        $validator = validator::make(json_decode($req->getContent(),true
        ), 
            ['name' => 'required|max:30',
             'description' => 'required|',
             'collection' => 'required|max:30'
            ]
        );

        if ($validator->fails()){
            $response['status'] = 0;
            $response['msg'] = $validator->errors();

        }else {
            $datos = $req->getContent();
            $datos = json_decode($datos);

            $card = new Card();

            $card->name = $datos->name;
            $card->description = $datos->description;
            $card->collection = $datos->collection;

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

    public function createCollection(Request $req){

        $response = ["status" => 1, "msg" => ""];

        $validator = validator::make(json_decode($req->getContent(),true
        ), 
            ['name' => 'required|max:30',
             'symbol' => 'required|max:30',
             'date' => 'required|max:30'
            ]
        );

        if ($validator->fails()){
            $response['status'] = 0;
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
                $response['status'] = 0;
                $response['msg'] = "An error has occurred: ".$e->getMessage();
            }
        }
        return response()->json($response);
    }

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

    public function searchCard(Request $req){
        $response = ["status" => 1, "msg" => ""];
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
               $response['status'] = 0;
               $response['msg'] = "An error has occurred: "; 
               Log::error('An error has occurred: ');
            }        
        }catch(\Exception $e){
           $response['status'] = 0;
           $response['msg'] = "An error has occurred: ".$e->getMessage(); 
           Log::error('An error has occurred: ');          
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

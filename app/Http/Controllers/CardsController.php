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
                    $response['msg'] = "Card save and asociate with colection";
                } catch (\Exception $e) {
                    $response['status'] = 400;
                    $response['msg'] = "An error has occurred: ".$e->getMessage();
                }
            } else {
                $response['status'] = 401;
                $response['msg'] = "This colection does not exist";
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
             'cards' => 'required'
            ]
        );

        if ($validator->fails()){
            $response['status'] = 402;
            $response['msg'] = $validator->errors();

        }else {
            $data = $req->getContent();
            $data = json_decode($data);
            $searchValidId =[];

            foreach ($data->cards as $addCard) {
                if(isset($addCard->id)){
                $card = Card::where('id','=',$addCard->id)->first();
                if($card){
                    array_push($searchValidId,$card->id);
                }
                }elseif (isset($addCard->name) && isset($addCard->description)) {     
                        $newCard = new Card();
                        $newCard->name = $addCard->name;
                        $newCard->description = $addCard->description;

                        try {
                            $newCard->save();
                            array_push($searchValidId,$newCard->id);
                            $response['msg'] = "Card save with id ".$newCard->id;
                                
                        } catch (\Exception $e) {
                            $response['status'] = 400;
                            $response['msg'] = "An error has occurred: ".$e->getMessage();
                        }
            }else{
                $response['status'] = 401;
                $response['msg'] = "The entered data does not exist";
            }    
        }
        if(!empty($searchValidId)){
            $cardId = implode (", ",$searchValidId); 
            try{
            $collection = new Collection();
            $collection->name = $data->name;
            $collection->symbol = $data->symbol;
            $collection->date = $data->date;
            $collection->save();

            foreach($searchValidId as $id){
                $cardCollection = new CardCollection();
                $cardCollection->card_id = $id;
                $cardCollection->collection_id = $collection->id;
                $cardCollection->save();
            }
            $response['msg'] = "The collection has been created and the cards have been associated";
            
            }catch (\Exception $e) {
                $response['status'] = 400;
                $response['msg'] = "An error has occurred: " .$e->getMessage();
            }
        }
        }
        return response()->json($response);
    }   
            
    public function createCardsSolds(Request $req){

        $response = ["status" => 200, "msg" => ""];

        $validator = validator::make(json_decode($req->getContent(),true
        ), 
            [
             'card_asociate' => 'required|max:30',
             'amount' => 'required|max:30',
             'price' => 'required|max:30|numeric'
            ]
        );

        if ($validator->fails()){
            $response['status'] = 402;
            $response['msg'] = $validator->errors();

        }else {
            $data = $req->getContent();
            $data = json_decode($data);

            $card = Card::where('id', '=', $data->card_id)->first();
            if($card){
                $cardSold = new cardSold();
                $cardSold->card_asociate = $data->card_asociate;
                $cardSold->amount = $data->amount;
                $cardSold->price = $data->price;
                $cardSold->user_asociate = $req->user->id;
                try {
                    $cardSold->save();
                    $response['msg'] = "Your Card put on sale with id ".$cardSold->id;
                } catch (\Exception $e) {
                    $response['status'] = 400;
                    $response['msg'] = "An error has occurred: ".$e->getMessage();
                }
            }else{
                $response['status'] = 401;
                $response['msg'] = "This card does not exist"; 
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
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Card;
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
}

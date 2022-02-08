<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Collection;
use Illuminate\Support\MessageBag;

class CollectionController extends Controller
{
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
}

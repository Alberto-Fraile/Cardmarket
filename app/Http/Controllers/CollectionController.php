<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Collection;
use Illuminate\Support\MessageBag;

class CollectionController extends Controller
{
    public function createCollection(Request $req){

        $response = ["status" => 1, "msg" => ""];
        $datos = $req->getContent();
        $datos = json_decode($datos);

        $collection = new Collection();

        $collection->name = $datos->name;
        $collection->symbol = $datos->symbol;

        try{
            $collection->save();
            $response['msg'] = "Collection save with id ".$collection->id;
        }catch(\Exception $e){
            $response['status'] = 0;
            $response['msg'] = "An error has occurred: ".$e->getMessage();
        }

        return response()->json($response);
    }
}

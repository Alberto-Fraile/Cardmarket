<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Collection;
use Illuminate\Support\MessageBag;

class CollectionController extends Controller
{
    public function createCollection(Request $req){

        $respuesta = ["status" => 1, "msg" => ""];
        $datos = $req->getContent();
        $datos = json_decode($datos);

        $collection = new Collection();

        $collection->name = $datos->name;
        $collection->simbolo = $datos->simbolo;

        try{
            $collection->save();
            $respuesta['msg'] = "Collection save with id ".$collection->id;
        }catch(\Exception $e){
            $respuesta['status'] = 0;
            $respuesta['msg'] = "Se ha producido un error: ".$e->getMessage();
        }

        return response()->json($respuesta);
    }
}

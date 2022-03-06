<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CardsController;
use App\Http\Controllers\CardsSoldsController;
use App\Http\Controllers\CollectionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::put('/createCard',[CardsController::class,'createCard']);

Route::prefix('user')->group(function(){
	Route::post('/login',[UsersController::class,'login']);
	Route::post('/recoveredPassword',[UsersController::class,'recoveredPassword']);
	Route::put('/register',[UsersController::class,'register']);
	Route::get('/searchBuyCard',[CardsController::class,'searchBuyCard']);
	
	Route::middleware('api_token','validation_admin')->group(function(){
		Route::put('/createCard',[CardsController::class,'createCard']);
		Route::put('/createCollection',[CardsController::class,'createCollection']);
		Route::put('/asociate_cards/{cards_id}/{collections_id}',[UsersController::class,'asociate_cards']);
	});

	Route::middleware('api_token', 'validation')->group(function(){
		Route::get('/searchCard',[CardsController::class,'searchCard']);
		Route::put('/createCardsSolds',[CardsController::class,'createCardsSolds']);
	});
});
	
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CardsController;
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


Route::post('/login',[UsersController::class,'login']);
Route::post('/recoveredPassword',[UsersController::class,'recoveredPassword']);

Route::middleware('api_token', 'validation', 'validation_admin')->prefix('user')->group(function(){
	Route::put('/register',[UsersController::class,'register']);
});

Route::middleware('api_token','validation_admin')->prefix('user')->group(function(){
	Route::put('/createCard',[CardsController::class,'createCard']);
	Route::put('/createCollection',[CollectionController::class,'createCollection']);
    Route::put('/asociate_cards/{cards_id}/{collection_id}',[UsersController::class,'asociate_cards']);
});

Route::middleware('api_token', 'validation')->prefix('user')->group(function(){
	Route::get('/searchCard',[CardsController::class,'searchCard']);
});
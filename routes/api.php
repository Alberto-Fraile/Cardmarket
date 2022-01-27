<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('validation_admin')->prefix('user')->group(function(){
	Route::put('/createCard',[CardsController::class,'createCard']);
	Route::put('/createCollection',[CollectionController::class,'createCollection']);
});

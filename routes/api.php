<?php

use App\Http\Controllers\API\Tag\TagController;
use App\Http\Controllers\API\Thread\ThreadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Answer\AnswerController;

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

Route::prefix('Auth')->group( function (){
    Route::post('register',[RegisterController::class,'store']);
    Route::post('login',[LoginController::class,'login']);
    Route::post('logout',[LoginController::class,'logout']);

});

Route::resource('tags', TagController::class);

Route::resource('threads', ThreadController::class);

Route::resource('answers',AnswerController::class)->except('index','show');

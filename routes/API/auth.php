<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\LoginController;


Route::prefix('Auth')->group( function (){
    Route::post('register',[RegisterController::class,'store']);
    Route::post('login',[LoginController::class,'login']);
    Route::post('logout',[LoginController::class,'logout']);

});

<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Thread\ThreadController;
use App\Http\Controllers\API\Answer\AnswerController;
use App\Http\Controllers\API\Tag\TagController;

Route::prefix('discuss')->group(function (){

    Route::resource('threads', ThreadController::class);
    Route::resource('answers',AnswerController::class)->except('index','show');
    Route::resource('tags', TagController::class);
    Route::post('threads/{thread}/solved',[ThreadController::class,'solveThread']);

});




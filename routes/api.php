<?php

use App\Http\Controllers\API\Tag\TagController;
use App\Http\Controllers\API\Thread\ThreadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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



//authentication routes
require __DIR__.'/API/auth.php';

//discussion routes
require __DIR__ . '/API/forum.php';

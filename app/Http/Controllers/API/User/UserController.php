<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnswerResource;
use App\Http\Resources\ThreadIndexResurece;
use App\Models\Answer;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showUserProfileByName(User $user, Request $request)
    {
        //get user id by route model binding
        $user_id = $user->id;

        //thread of specified user
        $thread=Thread::query()->where('user_id',$user_id)->get();

        //answer of specified user
        $answer = Answer::query()->where('user_id', $user_id)->get();

        //thread resource
        $thread = ThreadIndexResurece::collection($thread);

        //answer resource
        $answer = AnswerResource::collection($answer);

        return response()->json([
            'answer' => $answer,
            'thread' => $thread,
        ]);
    }


    public function leaderboard()
    {
        return User::query()->orderBy('score','desc')->paginate(25);
    }


}



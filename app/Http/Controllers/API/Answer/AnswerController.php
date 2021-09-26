<?php

namespace App\Http\Controllers\API\Answer;

use App\Http\Controllers\Controller;
use App\Http\Repository\UserRepository;
use App\Http\Requests\AnswerRequest;
use App\Models\Answer;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\NewReply;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class AnswerController extends Controller
{

    /**
     * AnswerController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param AnswerRequest $request
     * @return JsonResponse
     */
    public function store(AnswerRequest $request): JsonResponse
    {
        $thread_id = $request->input('thread_id');
        $answer = Answer::create([
            'content' => $request->input('content'),
            'user_id' => auth()->id(),
            'thread_id' => $thread_id,
        ]);

        //add reply count
        Thread::query()->where('id', $thread_id)->increment('replies_count');

        //add score of user who have answered
        if (Thread::find($thread_id)->user_id !== \auth()->id()) {
            User::where('id', $answer->user_id)->increment('score', 5);
        }

        //get thread for notification
        $threadOwner = Thread::find($thread_id);

        //get owner of thread
        $owner = User::query()->where('id', $threadOwner->user_id)->get();

        //send new reply notification for owner of thread
        Notification::send($owner, new NewReply($answer));

        return response()->json([
            'created' => true,
            'massage' => 'answer created successfully'
        ], Response::HTTP_CREATED);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Answer $answer
     * @return JsonResponse
     */
    public function destroy(Answer $answer): JsonResponse
    {
        $user = resolve(UserRepository::class)->getUserByIdForRole(Auth::id());


        if (Gate::allows('edit:answer', $answer) || $user->hasRole('answer-admin')) {
            $answer->delete();

            return response()->json([
                'status' => true,
                'massage' => 'answer deleted successfully'
            ], Response::HTTP_OK);

        } else {
            return response()->json([
                'status' => false,
                'massage' => 'access denied!'
            ], Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AnswerRequest $request
     * @param Answer $answer
     * @return JsonResponse
     *
     */
    public function update(AnswerRequest $request, Answer $answer): JsonResponse
    {
        $user = resolve(UserRepository::class)->getUserByIdForRole(Auth::id());

        if (Gate::allows('edit:answer', $answer) || $user->hasRole('answer-admin')) {
            $answer->update([
                'content' => $request->input('content')
            ]);

            return response()->json([
                'status' => true,
                'massage' => 'answer updated successfully'
            ], Response::HTTP_OK);

        } else {
            return response()->json([
                'status' => false,
                'massage' => 'access denied!'
            ], Response::HTTP_FORBIDDEN);
        }

    }
}

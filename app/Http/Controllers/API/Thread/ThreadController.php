<?php

namespace App\Http\Controllers\API\Thread;

use App\Http\Controllers\Controller;
use App\Http\Repository\UserRepository;
use App\Http\Requests\ThreadRequest;
use App\Http\Resources\ThreadIndexResurece;
use App\Models\TagThread;
use App\Models\Thread;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ThreadController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResource
     */
    public function index(Request $request): JsonResource
    {
        $filter = $request->input('filter');

        $threads = Thread::query();

        if ($filter === 'solved') {

            $threads = $threads->where('solve', 1)->latest()->paginate(10);

        } elseif ($filter === 'unsolved') {

            $threads = $threads->where('solve', 0)->latest()->paginate(10);

        } elseif ($filter === 'most-answered') {

            $threads = $threads->orderBy('replies_count', 'desc')->latest()->paginate(10);

        } else {

            $threads = $threads->latest()->paginate(10);
        }

        return ThreadIndexResurece::collection($threads);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ThreadRequest $request
     * @return JsonResponse
     */
    public function store(ThreadRequest $request): JsonResponse
    {
        $thread = Thread::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'slug' => Str::slug($request->input('title')),
            'user_id' => auth()->id(),
        ]);

        //insert data in pivot table
        TagThread::create([
            'tag_id' => $request->input('tag_id'),
            'thread_id' => $thread->id,
        ]);


        return response()->json([
            'massage' => 'thread created successfully',
            'created' => true
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Thread $thread
     * @return JsonResource
     */
    public function show(Thread $thread): JsonResource
    {
        $thread = Thread::find($thread);
        return ThreadIndexResurece::collection($thread);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ThreadRequest $request
     * @param Thread $thread
     * @return JsonResponse
     */
    public function update(ThreadRequest $request, Thread $thread): JsonResponse
    {

        //only admin and owner of thread can edit(delete or update)thread
        if (Gate::allows('edit:thread', $thread)) {
            $thread->update([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'slug' => Str::slug($request->input('title'))
            ]);

            return response()->json([
                'massage' => 'thread updated successfully',
                'created' => true
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'massage' => 'access denied!',
                'status' => false
            ], Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Thread $thread
     * @return JsonResponse
     */
    public function destroy(Thread $thread): JsonResponse
    {
        $user = resolve(UserRepository::class)->getUserByIdForRole(Auth::id());

        //only admin and owner of thread can edit(delete or update)thread
        if (Gate::allows('edit:thread', $thread) || $user->hasRole('thread-admin')) {


            $thread->delete();

            return response()->json([
                'massage' => 'thread deleted successfully',
                'status' => true
            ], Response::HTTP_OK);

        } else {

            return response()->json([
                'massage' => 'access denied!',
                'status' => false
            ], Response::HTTP_FORBIDDEN);
        }
    }


    public function solveThread(Request $request, Thread $thread)
    {
        //validate request input
        $request->validate([
            'solve' => 'required'
        ]);

        if ($request->input('solve') == 1) {

            //find the thread by route model binding
            $thread->update([
                'solve' => 1
            ]);

            return \response()->json([
                'status' => true,
                'massage' => 'thread solved and updated successfully'
            ], Response::HTTP_OK);
        }

        return Response::HTTP_UNPROCESSABLE_ENTITY;
    }
}

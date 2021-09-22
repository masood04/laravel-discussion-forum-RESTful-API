<?php

namespace App\Http\Controllers\API\Thread;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThreadRequest;
use App\Http\Resources\ThreadIndexResurece;
use App\Models\TagThread;
use App\Models\Thread;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ThreadController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('index','show');
    }

    /**
     * Display a listing of the resource.
     *
     *@return JsonResource
     * @param Request $request
     */
    public function index(Request $request): JsonResource
    {
        $solved = $request->input('solve');
        $unsolved = $request->input('unsolved');
        $popular = $request->input('popular');

        $threads = Thread::query();

        if ($solved) {
            $threads = $threads->where('solve', 1)->latest()->paginate(10);
        } elseif ($unsolved) {
            $threads = $threads->where('solve', 0)->latest()->paginate(10);
        } elseif ($popular) {
            $threads = $threads->orderBy('replies_count', 'desc')->latest()->paginate(10);
        } else {
            $threads= $threads->latest()->paginate(10);
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

        TagThread::create([
            'tag_id' => $request->input('tag_id'),
            'thread_id' => $thread->id,
        ]);


        return response()->json([
            'massage' => 'thread created successfully',
            'created' => true
        ],Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Thread $thread
     * @return JsonResource
     */
    public function show(Thread $thread): JsonResource
    {
        $thread = Thread::findOrFail($thread);
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
        $thread->update([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'slug' => Str::slug($request->input('title'))
        ]);

        return response()->json([
            'massage' => 'thread updated successfully',
            'created' => true
        ],Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Thread $thread
     * @return JsonResponse
     */
    public function destroy(Thread $thread): JsonResponse
    {

        $thread->delete();

        return response()->json([
            'massage' => 'thread deleted successfully',
            'created' => true
        ],Response::HTTP_CREATED);
    }
}

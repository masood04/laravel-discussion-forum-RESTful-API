<?php

namespace App\Http\Controllers\API\Tag;

use App\Http\Controllers\Controller;
use App\Http\Repository\UserRepository;
use App\Http\Resources\TagResurece;
use App\Http\Resources\ThreadIndexResurece;
use App\Http\Resources\ThreadShowResource;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TagController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     *@return JsonResource
     */
    public function index(): JsonResource
    {
        return TagResurece::collection(Tag::with('threads')->latest()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */

    public function store(Request $request): JsonResponse
    {
        $user = resolve(UserRepository::class)->getUserByIdForRole(Auth::id());

        //only admins can create tags
        if ($user->hasPermissionTo('tag-management')) {
            $request->validate(['name' => 'required|string|max:255']);

            Tag::create([
                'name' => $request->input('name'),
                'slug' => Str::slug($request->input('name'))
            ]);

            return response()->json([
                'massage' => 'tag created successfully',
                'crated' => true
            ], Response::HTTP_CREATED);
        } else {
            return response()->json([
                'massage' => 'You must be an administrator.',
                'status' => false
            ], Response::HTTP_FORBIDDEN);
        }

    }

    /**
     * Display a list of thread by tag's slug.
     *
     *
     * @param Tag $tag
     *@return JsonResource
     */
    public function show(Tag $tag): JsonResource
    {
        return ThreadShowResource::collection($tag->threads()->latest()->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Tag $tag
     * @return JsonResponse
     */
    public function update(Request $request, Tag $tag): JsonResponse
    {
        $request->validate(['name' => 'required|string|max:255']);
        $user = resolve(UserRepository::class)->getUserByIdForRole(Auth::id());

        //only admins can update tags
        if ($user->hasPermissionTo('tag-management')) {
            $tag->update([
                'name' => $request->input('name'),
                'slug' => Str::slug($request->input('name'))
            ]);

            return response()->json([
                'massage' => 'tag updated successfully',
                'status' => true
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'massage' => 'You must be an administrator.',
                'status' => false
            ], Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Tag $tag
     * @return JsonResponse
     */
    public function destroy(Tag $tag): JsonResponse
    {
        $tag->delete();

        $user = resolve(UserRepository::class)->getUserByIdForRole(Auth::id());

        if ($user->hasPermissionTo('tag-management')) {
            $tag->delete();
            return response()->json([
                'massage' => 'tag deleted successfully',
                'status' => true
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'massage' => 'You must be an administrator.',
                'status' => false
            ], Response::HTTP_FORBIDDEN);
        }
    }
}

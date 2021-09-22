<?php

namespace App\Http\Controllers\API\Tag;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResurece;
use App\Http\Resources\ThreadIndexResurece;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index()
    {
        return TagResurece::collection(Tag::with('threads')->latest()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $request->validate(['name'=>'required|string|max:255']);

        Tag::create([
            'name'=> $request->input('name'),
            'slug' => Str::slug($request->input('name'))
        ]);

        return response()->json([
            'massage' => 'tag created successfully',
            'crated' => true
        ],Response::HTTP_CREATED);
    }

    /**
     * Display a list of thread by tag's slug.
     *
     *
     * @param  \App\Models\Tag  $tag
     *
     */
    public function show(Tag $tag)
    {
        return ThreadIndexResurece::collection($tag->threads()->latest()->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return JsonResponse
     */
    public function update(Request $request, Tag $tag): JsonResponse
    {
        $request->validate(['name'=>'required|string|max:255']);

        $tag->update([
            'name'=> $request->input('name'),
            'slug' => Str::slug($request->input('name'))
        ]);

        return response()->json([
            'massage' => 'tag updated successfully',
            'status' => true
        ],Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return JsonResponse
     */
    public function destroy(Tag $tag): JsonResponse
    {
        $tag->delete();

        return response()->json([
            'massage' => 'tag deleted successfully',
            'status' => true
        ],Response::HTTP_OK);
    }
}

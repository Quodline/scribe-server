<?php

namespace App\Http\Controllers;

use App\Enum\PostFilter;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|Collection
    {
        $filterVal = $request->query('filter') ?? PostFilter::All->value;
        $filter = PostFilter::tryFrom($filterVal);

        if (empty($filter)) {
            return response()->json([
                'errors' => [
                    'filter' => [
                        sprintf(
                            "Filter value is invalid. Must be '%s'",
                            implode("' or '", PostFilter::caseValues())
                        )
                    ],
                ],
            ], 422);
        }

        $posts = $filter === PostFilter::All ? Post::latest()->get() : Auth::user()->posts()->latest()->get();

        return $posts;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'text_content' => 'required|string',
        ]);

        $request->user()->posts()->create($validated);

        return response()->json(null, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}

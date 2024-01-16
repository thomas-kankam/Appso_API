<?php

namespace App\Http\Controllers\Post;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Get a list of all the posts
    public function index()
    {
        try {
            $posts = Post::orderBy('created_at', 'desc')
                ->with('doctor:id,full_name,passport_picture')
                ->withCount('comments', 'likes')
                ->get();

            return response()->json(['posts' => $posts], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch posts.'], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    // Create a new post
    public function store(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'doctor_id' => 'required|integer',
                'title' => 'required|string',
                'content' => 'required|string',
                'images' => 'required|array',
                'images.*' => 'required|string',
            ]);

            // Create the post
            $post = Post::create([
                'doctor_id' => auth()->user()->id,
                'title' => $request->title,
                'content' => $request->content,
                'images' => $request->images,
            ]);

            // Return a JSON response
            return response()->json(['post' => $post], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create post.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    // Get a single post
    public function show(string $id)
    {
        try {
            $post = Post::where('id', $id)
                ->with('doctor:id,full_name,passport_picture')
                ->withCount('comments', 'likes')
                ->first();

            if (!$post) {
                return response()->json(['error' => 'Post not found.'], 404);
            }

            return response()->json(['post' => $post], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch the post.'], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    // Update a single post
    public function update(Request $request, string $id)
    {
        try {
            // Validate the request
            $request->validate([
                'title' => 'required|string',
                'content' => 'required|string',
                'images' => 'required|array',
                'images.*' => 'required|string',
            ]);

            // Find the post
            $post = Post::find($id);

            // Check if the post exists
            if (!$post) {
                return response()->json(['error' => 'Post not found.'], 404);
            }

            // Check if the user is authorized to update the post
            if (!auth()->user()->id === $post->doctor_id) {
                return response()->json(['error' => 'You are not authorized to update this post.'], 403);
            }

            // Update the post
            $post->update([
                'title' => $request->title,
                'content' => $request->content,
                'images' => $request->images,
            ]);

            // Return a JSON response
            return response()->json(['post' => $post], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update post.'], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find the post
            $post = Post::find($id);

            // Check if the post exists
            if (!$post) {
                return response()->json(['error' => 'Post not found.'], 404);
            }

            // Check if the user is authorized to delete the post
            if (!auth()->user()->id === $post->doctor_id) {
                return response()->json(['error' => 'You are not authorized to delete this post.'], 403);
            }

            // Delete the comments
            $post->comments()->delete();

            // Delete the likes
            $post->likes()->delete();

            // Delete the post
            $post->delete();

            // Return a JSON response
            return response()->json(['message' => 'Post deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete post.'], 500);
        }
    }
}

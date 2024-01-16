<?php

namespace App\Http\Controllers\Reply;

use App\Models\Post;
use App\Models\Reply;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        try {
            // Find the post
            $post = Post::find($id);

            // Check if the post exists
            if (!$post) {
                return response()->json(['error' => 'Post not found.'], 404);
            }

            // Retrieve replies with eager loading and pagination
            $replies = Reply::where('comment_id', $id)
                ->orderBy('created_at', 'desc')
                ->with('doctor:id,full_name,passport_picture')
                ->with('patient:id,full_name,passport_picture')
                ->paginate(10); // Adjust the number of replies per page

            // Return a JSON response with replies and post information
            return response()->json([
                'post' => $post,
                'replies' => $replies,
            ], 200);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            \Log::error('Exception occurred: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            // Handle exceptions and return an error response
            return response()->json(['error' => 'Failed to fetch replies.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        try {
            // Validate the request
            $request->validate([
                'reply' => 'required|string',
            ]);

            // Find the post
            $post = Post::find($id);

            // Check if the post exists
            if (!$post) {
                return response()->json(['error' => 'Post not found.'], 404);
            }

            // Create a new reply
            $reply = new Reply();
            $reply->comment_id = $id;
            $reply->doctor_id = Auth::guard('doctor')->user()->id;
            $reply->patient_id = Auth::guard('patient')->user()->id;
            $reply->reply = $request->input('reply');
            $reply->save();

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => 'Reply posted successfully.',
                'data' => $reply,
            ], 201);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            \Log::error('Exception occurred: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            // Handle exceptions and return an error response
            return response()->json(['error' => 'Failed to post reply.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Validate the request
            $request->validate([
                'reply' => 'required|string',
            ]);

            // Find the reply
            $reply = Reply::find($id);

            // Check if the reply exists
            if (!$reply) {
                return response()->json(['error' => 'Reply not found.'], 404);
            }

            // Update the reply
            $reply->reply = $request->input('reply');
            $reply->save();

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => 'Reply updated successfully.',
                'data' => $reply,
            ], 200);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            \Log::error('Exception occurred: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            // Handle exceptions and return an error response
            return response()->json(['error' => 'Failed to update reply.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find the reply
            $reply = Reply::find($id);

            // Check if the reply exists
            if (!$reply) {
                return response()->json(['error' => 'Reply not found.'], 404);
            }

            // Delete the reply
            $reply->delete();

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => 'Reply deleted successfully.',
                'data' => $reply,
            ], 200);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            \Log::error('Exception occurred: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            // Handle exceptions and return an error response
            return response()->json(['error' => 'Failed to delete reply.'], 500);
        }
    }
}

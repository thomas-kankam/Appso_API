<?php

namespace App\Http\Controllers\Comment;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Get a list of all the comments for a post
    public function index($id)
    {
        try {
            // Find the post
            $post = Post::find($id);

            // Check if the post exists
            if (!$post) {
                return response()->json(['error' => 'Post not found.'], 404);
            }

            // Retrieve comments with eager loading and pagination
            $comments = Comment::where('post_id', $id)
                ->orderBy('created_at', 'desc')
                ->with('doctor:id,full_name,passport_picture')
                ->with('patient:id,full_name,passport_picture')
                ->paginate(10); // Adjust the number of comments per page

            // Return a JSON response with comments and post information
            return response()->json([
                'post' => $post,
                'comments' => $comments,
            ], 200);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            \Log::error('Exception occurred: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            // Handle exceptions and return an error response
            return response()->json(['error' => 'Failed to fetch comments.'], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    // Create a new comment
    public function store(Request $request, $id)
    {
        try {
            // Validate the request
            $request->validate([
                'comment' => 'required|string',
            ]);

            // Find the post
            $post = Post::find($id);

            // Check if the post exists
            if (!$post) {
                return response()->json(['error' => 'Post not found.'], 404);
            }

            $doctorId = null;
            $patientId = null;

            if (Auth::user()->role === 'doctor') {
                $doctorId = Auth::user()->id;
            } elseif (Auth::user()->role === 'patient') {
                $patientId = Auth::user()->id;
            }

            // Create the comment
            $comment = Comment::create([
                'doctor_id' => $doctorId,
                'patient_id' => $patientId,
                'post_id' => $id,
                'comment' => $request->comment,
            ]);

            // Return a JSON response
            return response()->json(['comment' => $comment, 'message' => 'Comment created successfully.'], 201);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            \Log::error('Exception occurred: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            // Handle exceptions and return an error response
            return response()->json(['error' => 'Failed to create comment.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    // Update a comment
    public function update(Request $request, string $id)
    {
        try {
            // Validate the request
            $request->validate([
                'comment' => 'required|string',
            ]);

            // Find the comment
            $comment = Comment::find($id);

            // Check if the comment exists
            if (!$comment) {
                return response()->json(['error' => 'Comment not found.'], 404);
            }

            // Check if the authenticated user is the owner of the comment
            if (Auth::user()->id !== $comment->doctor_id && Auth::user()->id !== $comment->patient_id) {
                return response()->json(['error' => 'You can only edit your own comments.'], 403);
            }

            // Update the comment
            $comment->update([
                'comment' => $request->comment,
            ]);

            // Return a JSON response
            return response()->json(['comment' => $comment, 'message' => 'Comment updated successfully.'], 200);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            \Log::error('Exception occurred: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            // Handle exceptions and return an error response
            return response()->json(['error' => 'Failed to update comment.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // Delete a comment
    public function destroy(string $id)
    {
        try {
            // Find the comment
            $comment = Comment::find($id);

            // Check if the comment exists
            if (!$comment) {
                return response()->json(['error' => 'Comment not found.'], 404);
            }

            // Check if the authenticated user is the owner of the comment
            if (Auth::user()->id !== $comment->doctor_id && Auth::user()->id !== $comment->patient_id) {
                return response()->json(['error' => 'You can only delete your own comments.'], 403);
            }

            // Delete the comment
            $comment->delete();

            // Return a JSON response
            return response()->json(['message' => 'Comment deleted successfully.'], 200);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            \Log::error('Exception occurred: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            // Handle exceptions and return an error response
            return response()->json(['error' => 'Failed to delete comment.'], 500);
        }
    }
}

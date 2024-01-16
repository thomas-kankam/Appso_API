<?php

namespace App\Http\Controllers\Like;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function likeOrUnlike($id)
    {
        try {
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

            $like = $post->likes()->where('doctor_id', $doctorId)->where('patient_id', $patientId)->first();

            // if not liked then like
            if (!$like) {
                $like = new Like();
                $like->doctor_id = $doctorId;
                $like->patient_id = $patientId;
                $like->post_id = $id;
                $like->save();

                return response()->json(['message' => 'Post liked.'], 200);
            }

            // else dislike it
            $like->delete();

            return response()->json(['message' => 'Post disliked.'], 200);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            \Log::error('Exception occurred: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            // Handle exceptions and return an error response
            return response()->json(['error' => 'Failed to fetch comments.'], 500);
        }
    }
}

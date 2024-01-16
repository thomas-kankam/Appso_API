<?php

namespace App\Http\Controllers\PostCategory;

use App\Models\PostCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $postCategories = PostCategory::all();
            return response()->json([
                'success' => true,
                'message' => 'Post categories retrieved successfully',
                'data' => $postCategories
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Post categories could not be retrieved',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $postCategory = PostCategory::create($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Post category created successfully',
                'data' => $postCategory
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Post category could not be created',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $postCategory = PostCategory::find($id);
            $postCategory->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Post category updated successfully',
                'data' => $postCategory
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Post category could not be updated',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $postCategory = PostCategory::find($id);
            $postCategory->delete();
            return response()->json([
                'success' => true,
                'message' => 'Post category deleted successfully',
                'data' => $postCategory
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Post category could not be deleted',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}

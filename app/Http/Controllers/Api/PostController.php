<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class PostController extends Controller
{

    public function index(Request $request)
    {
        $query = Post::query()->with('author');

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->author_id) {
            $query->where('author_id', $request->author_id);
        }

        if ($request->from && $request->to) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($posts);
    }


    public function store(Request $request)
    {
        //  return response()->json(['message' => 'Store method called']);
        $user = JWTAuth::parseToken()->authenticate();

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:Technology,Lifestyle,Education',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'author_id' => $user->id,
        ]);

        return response()->json($post, 201);
    }

    public function show($id)
    {
        $post = Post::with('author', 'comments.user')->find($id);

        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        return response()->json($post);
    }
    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        // If user is author but not the owner, forbid
        if ($user->role === 'author' && $post->author_id !== $user->id) {
            return response()->json(['error' => 'Forbidden: not your post'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'category' => 'sometimes|in:Technology,Lifestyle,Education',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        $post->update($request->all());

        return response()->json($post);
    }

    public function destroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        // If user is author but not the owner, forbid
        if ($user->role === 'author' && $post->author_id !== $user->id) {
            return response()->json(['error' => 'Forbidden: not your post'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }

    public function search_filter(Request $request)
    {
        $query = Post::query()->with('author');


        // 🔍 Search
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('content', 'like', "%$search%")
                    ->orWhere('category', 'like', "%$search%");
            });
        }

        // Filter by category
        if ($request->category) {
            $query->where('category', $request->category);
        }

        // Filter by author
        if ($request->author_id) {
            $query->where('author_id', $request->author_id);
        }

        // Filter by date range
        if ($request->from && $request->to) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($posts);
    }
}

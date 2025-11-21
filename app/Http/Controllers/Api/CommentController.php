<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class CommentController extends Controller
{
    //
    public function store(Request $request, $postId)
    {
        $user = JWTAuth::parseToken()->authenticate();


        $post = Post::find($postId);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

      $validator=  Validator::make($request->all(), [

        'content' => 'Required|string',

    ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $comment = Comment::create([
            'post_id' => $postId,
            'user_id' => $user->id,
            'content' => $request->content,
        ]);

        return response()->json($comment, 201);
    }
}
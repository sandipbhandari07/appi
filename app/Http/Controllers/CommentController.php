<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index($postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $comments = $post->comments;

        return response()->json($comments, 200);
    }

    public function store(Request $request, $postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $request->validate([
            'user_name' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $comment = new Comment([
            'user_name' => $request->user_name,
            'content' => $request->content,
        ]);

        $post->comments()->save($comment);

        return response()->json($comment, 201);
    }

    public function show($postId, $id)
    {
        // if post exists
        $post = Post::find($postId);
    
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
    
        // if comment exists belongs to the post
        $comment = $post->comments()->find($id);
    
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }
    
        return response()->json($comment, 200);
    }
    
    public function update(Request $request, $postId, $id)
    {
        $post = Post::find($postId);
    
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
    
        $comment = $post->comments()->find($id);
    
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }
    
        $request->validate([
            'content' => 'sometimes|required|string',
            'user_name' => 'sometimes|required|string|max:255',
        ]);
    
        // Update the comment
        $comment->update($request->only(['content', 'user_name']));
    
        return response()->json($comment, 200);
    }
    public function destroy($postId, $id)
    {
        $post = Post::find($postId);
    
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
    
        $comment = $post->comments()->find($id);
    
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }
    
        $comment->delete();
    
        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }
    
}

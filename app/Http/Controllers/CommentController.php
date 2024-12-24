<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Get all comments for a specific post.
     */
    public function index($postId)
    {
        dd(auth()->user());
        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $comments = $post->comments;

        return response()->json($comments, 200);
    }

    /**
     * Store a new comment for a specific post.
     */
    public function store(Request $request, $postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $request->validate([
            'content' => 'required|string',
        ]);

        $comment = new Comment([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        $post->comments()->save($comment);

        return response()->json($comment, 201);
    }

    /**
     * Show a specific comment for a post.
     */
    public function show($postId, $id)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $comment = $post->comments()->find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        return response()->json($comment, 200);
    }

    /**
     * Update a specific comment.
     */
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'You are not authorized to update this comment'], 403);
        }

        $request->validate([
            'content' => 'sometimes|required|string',
        ]);

        $comment->update($request->only(['content']));

        return response()->json($comment, 200);
    }

    /**
     * Delete a specific comment.
     */
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

        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'You are not authorized to delete this comment'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }
}

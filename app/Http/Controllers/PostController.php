<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('comments')->get()->map(function ($post) {
            if ($post->image) {
                $post->image = str_replace('127.0.0.1', 'localhost', url('storage/' . $post->image));
            }
            return $post;
        });

        return response()->json($posts, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,passive',
            'descriptions' => 'nullable|string',
            'date' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'status', 'descriptions', 'date']);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $data['image'] = $imagePath;
        }

        $post = Post::create($data);

        if ($post->image) {
            $post->image = str_replace('127.0.0.1', 'localhost', url('storage/' . $post->image));
        }

        return response()->json($post, 201);
    }

    public function show($id)
    {
        $post = Post::with('comments')->find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        if ($post->image) {
            $post->image = str_replace('127.0.0.1', 'localhost', url('storage/' . $post->image));
        }

        return response()->json($post, 200);
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'status' => 'sometimes|in:active,passive',
            'descriptions' => 'nullable|string',
            'date' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'status', 'descriptions', 'date']);

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $imagePath = $request->file('image')->store('images', 'public');
            $data['image'] = $imagePath;
        }

        $post->update($data);

        if ($post->image) {
            $post->image = str_replace('127.0.0.1', 'localhost', url('storage/' . $post->image));
        }

        return response()->json($post, 200);
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}
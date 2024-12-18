<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

Route::resource('posts', PostController::class);

Route::prefix('posts/{postId}/comments')->group(function () {
    Route::get('/', [CommentController::class, 'index']);
    Route::post('/', [CommentController::class, 'store']);
    Route::get('{id}', [CommentController::class, 'show']);
    Route::put('{id}', [CommentController::class, 'update']);
    Route::delete('{id}', [CommentController::class, 'destroy']);
});

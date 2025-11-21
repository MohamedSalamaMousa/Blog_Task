<?php

use App\Http\Controllers\Api\Auth\JWTAuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [JWTAuthController::class, 'register']);
Route::post('login', [JWTAuthController::class, 'login']);

Route::group(['middleware' => ['jwt.auth']], function () {
    Route::get('user', [JWTAuthController::class, 'getUser']); // get current authenticated user
    Route::post('logout', [JWTAuthController::class, 'logout']); // logout
    Route::get('posts', [PostController::class, 'index']);
    Route::get('posts/{id}', [PostController::class, 'show']);
    Route::get('search_filter', [PostController::class, 'search_filter']);

    Route::post('posts', [PostController::class, 'store']);
    Route::put('posts/{id}', [PostController::class, 'update']);
    Route::delete('posts/{id}', [PostController::class, 'destroy']);


    Route::post('posts/{id}/comments', [CommentController::class, 'store']);
});
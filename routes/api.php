<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');
});

Route::middleware(['auth:api', 'permission:manage users'])->get('/admin-test', function () {
    return response()->json(['message' => 'Halo Admin, kamu punya izin!']);
});

Route::middleware(['auth:api'])->group(function () {
    Route::get('/projects', [ProjectController::class, 'index'])->middleware('permission:view dashboard');
    Route::post('/projects', [ProjectController::class, 'store'])->middleware('permission:create project');
    Route::get('/projects/{id}', [ProjectController::class, 'show'])->middleware('permission:view dashboard');
    Route::put('/projects/{id}', [ProjectController::class, 'update'])->middleware('permission:update project');
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->middleware('permission:delete project');
});

Route::middleware(['auth:api'])->group(function () {
    Route::get('/projects/{project}/tasks', [TaskController::class, 'index']);
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])->middleware('permission:assign tasks');
    Route::get('/tasks/{id}', [TaskController::class, 'show']);
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->middleware('permission:update tasks');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->middleware('permission:assign tasks');
});

Route::middleware('auth:api')->group(function () {
    Route::get('/tasks/{task}/comments', [CommentController::class, 'index']);
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store']);
});

Route::middleware('auth:api')->get('/dashboard', [DashboardController::class, 'index']);

Route::middleware('auth:api')->put('/profile', [UserController::class, 'updateProfile']);
Route::get('/users', [UserController::class, 'index']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);
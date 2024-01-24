<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes go here
Route::middleware('auth:api')->group(function () {
    // User logout
    Route::post('/logout', [AuthController::class, 'logout']);
    // Token refresh
    Route::post('/refresh', [AuthController::class, 'refresh']);
    // All blogs
    Route::get('/blogs', [BlogController::class, 'index']);

    // User-specific blogs
    Route::get('/my-blogs', [BlogController::class, 'userBlogs']);
    Route::post('/blogs', [BlogController::class, 'store']);
    Route::put('/blogs/{blog}', [BlogController::class, 'update']);
    Route::delete('/blogs/{blog}', [BlogController::class, 'destroy']);
});

// Custom error response for unauthenticated users
Route::fallback(function () {
    return response()->json(['error' => 'Error', 'message' => 'You are not allowed to access this route.'], 401);
});
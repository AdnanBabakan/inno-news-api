<?php

use App\Http\Controllers\PublicControllers\GeneralController;
use App\Http\Controllers\PublicControllers\PostController;
use App\Http\Controllers\PublicControllers\PublisherController;
use App\Http\Controllers\PublicControllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('/v1')->group(function() {

    Route::get('/test', [GeneralController::class, 'test']);

    Route::prefix('/general')->group(function() {
        Route::get('/', [GeneralController::class, 'getSettings']);
    });

    Route::prefix('/user')->group(function() {
        Route::post('/register', [UserController::class, 'register']);
        Route::post('/authenticate', [UserController::class, 'authenticate']);
    });

    Route::prefix('/posts')->group(function() {
        Route::get('/', [PostController::class, 'index']);
    });

    Route::prefix('/publishers')->group(function() {
        Route::get('/', [PublisherController::class, 'index']);
    });
});

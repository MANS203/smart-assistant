<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\TaskController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1/users')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:api')->get('/profile', [AuthController::class, 'getProfile']);
    Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);
});
Route::prefix('v1/tasks')->group(function () {
    Route::post('/add', [TaskController::class, 'addTask']);
    Route::put('/update/{id}', [TaskController::class, 'updateTask']);
    Route::delete('/delete/{id}', [TaskController::class, 'deleteTask']);
    Route::middleware('auth:api')->get('/get', [TaskController::class, 'getTask']);
    
});
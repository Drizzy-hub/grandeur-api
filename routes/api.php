<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PosterUploadsController;
use App\Http\Controllers\PosterController;
use App\Http\Controllers\PosterRefundController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/login', [AuthController::class, 'posterLogin']);
// Route::post('/register', [AuthController::class, 'posterRegister']);
Route::group(['prefix' => 'poster', 'namespace' => 'Poster'], function($router){
    // Route::post('/login', [AuthController::class, 'posterLogin']);
    Route::post('/register', [AuthController::class, 'posterRegister']);
    Route::post('/logout', [AuthController::class, 'posterLogout']);

    Route::group(['middleware' => ['assign.guard:poster', 'jwt.auth']], function(){
        Route::get('/profile', [AuthController::class, 'userProfile']);
        Route::post('/upload', [PosterUploadsController::class, 'uploadTask']);
        Route::get('/tasks', [PosterUploadsController::class, 'uploadedTasks']);
        Route::get('/tasks/{fileid}', [PosterUploadsController::class, 'ATask']);

        Route::post('/refund', [PosterRefundController::class, 'getRefund']);
        Route::get('/refunds/all', [PosterRefundController::class, 'getMyRefunds']);
    });
});

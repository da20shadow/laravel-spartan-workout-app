<?php

use App\Http\Controllers\Api\ExercisesController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

//Route::middleware('auth:sanctum')->group(function () {
//
//    Route::apiResource('exercises', ExercisesController::class);
//    Route::apiResource('users', UserController::class);
//    }
//);

/** PUBLIC routes */
Route::post('login',[AuthController::class,'login']);
Route::post('register',[AuthController::class,'register']);

/** Protected routes */
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('logout',[AuthController::class,'logout']);

    Route::get('exercises/history',[ExercisesController::class,'index']);

    Route::post('add/workout',[ExercisesController::class,'store']);

    Route::get('exercises',[ExercisesController::class,'showToday']);
    Route::get('exercises/{id}',[ExercisesController::class,'show']);
});

//Route::apiResource('exercises', ExercisesController::class);
//Route::apiResource('users', UserController::class);

<?php

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

Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group([
    'as' => 'api.',
    'middleware' => ['auth:sanctum']
], function () {
    Route::get('orders', [\App\Http\Controllers\Api\OrderController::class, 'list']);
    Route::get('order/{order}', [\App\Http\Controllers\Api\OrderController::class, 'show']);
    Route::get('order/{order}/discount', [\App\Http\Controllers\Api\OrderController::class, 'discount']);
    Route::post('/order', [\App\Http\Controllers\Api\OrderController::class, 'store']);
    Route::delete('order/{order}', [\App\Http\Controllers\Api\OrderController::class, 'destroy']);
});

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'user'],function(){
    Route::get('products',[ProductController::class,'index']);
    Route::resource('cartitems',CartItemController::class);
    Route::get('profile',[UserController::class,'index']);
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'],function(){
    Route::resource('products',ProductController::class);
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
});

Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);

<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Routing\Route as ComponentRoutingRoute;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout','logout')->middleware('auth:sanctum');
});

Route::controller(ProductController::class)->group(function () {
    Route::get('products', 'show');
    Route::post('products', 'create');
    Route::post('products/{id}', 'update');
    Route::delete('products/{id}', 'delete');

    Route::get('product_cat', 'search');
});

Route::controller(CategoryController::class)->group(function () {
    Route::get('cats', 'show');
    Route::post('cats', 'create');
    Route::post('cats/{id}', 'update');
});

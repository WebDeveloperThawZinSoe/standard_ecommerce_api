<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\HomeDataController;
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

// Public API Routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
Route::get("/home", [HomeDataController::class, "index"]);
Route::get("/faqs",[HomeDataController::class, "getFaqs"]);
Route::get("/privay_policy",[HomeDataController::class, "getPrivacyPolicy"]);
Route::get("/brands",[HomeDataController::class, "getBrands"]);
// Route::get("/brands/{id}",[HomeDataController::class, "getBrandProducts"]);
Route::get("/categories",[HomeDataController::class, "getCategories"]);
Route::get("/latest_products",[HomeDataController::class, "getLatestProducts"]);
Route::get("/product/{id}",[HomeDataController::class, "getProduct"]);
Route::post("/order/track",[HomeDataController::class, "trackOrder"]);

// Authenticated API Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'getUser']);
    Route::put('/user/update', [UserController::class, 'updateUser']);
    Route::get('/user/orders', [UserController::class, 'getUserOrders']);
});

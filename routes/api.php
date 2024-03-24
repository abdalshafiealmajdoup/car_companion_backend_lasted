<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ServiceCenterController;

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

// Customer Routes
Route::post('/customers/register', [CustomerController::class, 'register']);
Route::post('/customers/login', [CustomerController::class, 'login']);
Route::post('/customers/logout', [CustomerController::class, 'logout'])->middleware('auth:sanctum');

Route::get("/customers", [CustomerController::class, "index"]);
Route::get("/customers/{id}", [CustomerController::class, "show"]);
Route::put("/customers/{id}", [CustomerController::class, "update"]);
Route::delete("/customers/{id}", [CustomerController::class, "destroy"]);
Route::get("/customer-orders/{customer_id}", [CustomerController::class, "getOrdersList"]);




// Order Routes
Route::get("/orders", [OrderController::class, "index"]);
Route::post("/orders", [OrderController::class, "store"]);
Route::get("/orders/{id}", [OrderController::class, "show"]);
Route::put("/orders/{id}", [OrderController::class, "update"]);
Route::delete("/orders/{id}", [OrderController::class, "destroy"]);

// Service Routes
Route::get("/services", [ServiceController::class, "index"]);
Route::post("/services", [ServiceController::class, "store"]);
Route::get("/services/{id}", [ServiceController::class, "show"]);
Route::put("/services/{id}", [ServiceController::class, "update"]);
Route::delete("/services/{id}", [ServiceController::class, "destroy"]);

// Admin Routes
Route::get("/admins", [AdminController::class, "index"]);
Route::post('/admins/register', [AdminController::class, 'register']);
Route::post('/admins/login', [AdminController::class, 'login']);
Route::middleware('auth:admin')->group(function () {
Route::post('/admins/logout', [AdminController::class, 'logout']);
    Route::get("/admins/{id}", [AdminController::class, "show"]);
    Route::put("/admins/{id}", [AdminController::class, "update"]);
    Route::delete("/admins/{id}", [AdminController::class, "destroy"]);
});

// Address Routes
Route::get("/addresses", [AddressController::class, "index"]);
Route::post("/addresses", [AddressController::class, "store"]);
Route::get("/addresses/{id}", [AddressController::class, "show"]);
Route::put("/addresses/{id}", [AddressController::class, "update"]);
Route::delete("/addresses/{id}", [AddressController::class, "destroy"]);

// ServiceCenter Routes
Route::get("/service-centers", [ServiceCenterController::class, "index"]);
Route::post('/service-centers/login', [ServiceCenterController::class, 'login']);
Route::post('/service-centers/register', [ServiceCenterController::class, 'register']);


Route::middleware('auth:service_center')->group(function () {
    Route::post('/service-centers/logout', [ServiceCenterController::class, 'logout']);
    Route::get("/service-centers/{id}", [ServiceCenterController::class, "show"]);
    Route::put("/service-centers/{id}", [ServiceCenterController::class, "update"]);
    Route::delete("/service-centers/{id}", [ServiceCenterController::class, "destroy"]);
});

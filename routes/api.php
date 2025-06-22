<?php

   use Illuminate\Support\Facades\Route;
   use App\Http\Controllers\Api\AuthController;
   use App\Http\Controllers\Api\ProductoController;
   use App\Http\Controllers\Api\ReporteController;
   use App\Http\Controllers\Api\CajeroController;

   Route::post('/register', [AuthController::class, 'register']);
   Route::post('/login', [AuthController::class, 'login']);

   Route::middleware('auth:sanctum')->group(function () {
       Route::get('/user', [AuthController::class, 'user']);
       Route::post('/logout', [AuthController::class, 'logout']);

       // User routes (view products)
       Route::middleware('role:usuario')->group(function () {
           Route::get('/productos', [ProductoController::class, 'index']);
           Route::get('/productos/{id}', [ProductoController::class, 'show']);
       });

       // Cashier routes (transactions and cash register closure)
       Route::middleware('role:cajero')->prefix('cajero')->group(function () {
           Route::post('/sale', [CajeroController::class, 'sale']);
           Route::get('/transactions', [CajeroController::class, 'transactions']);
           Route::post('/close', [CajeroController::class, 'close']);
       });

       // Admin routes (reports)
       Route::middleware('role:admin')->prefix('admin')->group(function () {
           Route::get('/reportes', [ReporteController::class, 'index']);
       });
   });

   // Role middleware
   Route::middleware('auth:sanctum')->get('/check-role', function () {
       return response()->json(['role' => auth()->user()->role]);
   });
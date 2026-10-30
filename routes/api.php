<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerNotificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/users', [AuthController::class, 'storeUser']);
    
    Route::apiResource('/expenses', ExpensesController::class);
    
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('inventory', InventoryController::class);
    Route::apiResource('tasks', TaskController::class);

    Route::get('/sales', [SaleController::class, 'index']);
    Route::post('/sales', [SaleController::class, 'store']);
    Route::get('/sales/{sale}', [SaleController::class, 'show']);
    Route::get('/sales/{sale}/receipt', [SaleController::class, 'receipt']);

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/reports/financial', [DashboardController::class, 'financialReport']);

    Route::get('/notifications', [CustomerNotificationController::class, 'index']);
    Route::post('/notifications/{notification}/read', [CustomerNotificationController::class, 'markRead']);


    Route::get('/admin/dashboard', function (Request $request) {
        if (! $request->user()->isAdmin()) {
            abort(403, 'Only admins are allowed to access this route.');
        }

        return app(DashboardController::class)->adminDashboard();
    });
});

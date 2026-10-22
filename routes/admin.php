<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\RedemptionController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AuditLogController;

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('login', [AuthController::class, 'showLoginForm'])
        ->name('login');
    Route::post('login', [AuthController::class, 'login'])
        ->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::middleware(['auth', 'role:ADMIN,SUPERVISOR'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Users
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{uid}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{uid}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{uid}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{uid}', [UserController::class, 'destroy'])->name('users.destroy');

        // Transactions
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');

        // Redemptions
        Route::get('/redemptions', [RedemptionController::class, 'index'])->name('redemptions.index');
        Route::get('/redemptions/{id}', [RedemptionController::class, 'show'])->name('redemptions.show');
        Route::patch('/redemptions/{id}/status', [RedemptionController::class, 'updateStatus'])->name('redemptions.updateStatus');

        // Incentives (Catálogo de premios para canje en app móvil)
        Route::post('/incentives/seed-defaults', [\App\Http\Controllers\Admin\IncentiveController::class, 'seedDefaults'])->name('incentives.seedDefaults');
        Route::patch('/incentives/{id}/toggle', [\App\Http\Controllers\Admin\IncentiveController::class, 'toggleAvailability'])->name('incentives.toggle');
        Route::resource('incentives', \App\Http\Controllers\Admin\IncentiveController::class);

        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

        // Products
        Route::post('/products', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
        Route::put('/products/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/users/csv', [ReportController::class, 'exportUsersCsv'])->name('reports.users.csv');
        Route::get('/reports/transactions/csv', [ReportController::class, 'exportTransactionsCsv'])->name('reports.transactions.csv');
        Route::get('/reports/redemptions/csv', [ReportController::class, 'exportRedemptionsCsv'])->name('reports.redemptions.csv');

        // Audit
        Route::get('/audit', [AuditLogController::class, 'index'])->name('audit.index');
    });
});

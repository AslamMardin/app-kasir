<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // POS
    Route::get('/pos', [POSController::class, 'index']);
    Route::post('/pos/search', [POSController::class, 'searchProduct']);
    Route::post('/pos/store', [POSController::class, 'store']);
    Route::get('/pos/receipt/{transaction}', [POSController::class, 'receipt']);

    // Master Data
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('members', MemberController::class);

    // Shift
    Route::get('/shift/open', [ShiftController::class, 'open']);
    Route::post('/shift/open', [ShiftController::class, 'storeOpen']);
    Route::get('/shift/close', [ShiftController::class, 'close']);
    Route::post('/shift/close', [ShiftController::class, 'storeClose']);

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show']);
    Route::post('/transactions/{transaction}/void', [TransactionController::class, 'void']);

    // Reports
    Route::get('/reports/daily', [ReportController::class, 'daily']);
    Route::get('/reports/daily/pdf', [ReportController::class, 'downloadPdf']);

    // Settings
    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index']);
    Route::post('/settings/update', [\App\Http\Controllers\SettingController::class, 'update']);
    Route::post('/settings/password', [\App\Http\Controllers\SettingController::class, 'changePassword']);
    Route::get('/settings/backup', [\App\Http\Controllers\SettingController::class, 'backup']);
});

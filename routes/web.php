<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Transactions
Route::prefix('transactions')->name('transactions.')->group(function () {
    Route::get('/', [TransactionController::class, 'index'])->name('index');
    Route::post('/', [TransactionController::class, 'store'])->name('store');
    Route::delete('/{id}', [TransactionController::class, 'destroy'])->name('destroy');
});

// Accounts
Route::prefix('accounts')->name('accounts.')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('index');
    Route::post('/', [AccountController::class, 'store'])->name('store');
    Route::put('/{id}', [AccountController::class, 'update'])->name('update');
    Route::delete('/{id}', [AccountController::class, 'destroy'])->name('destroy');
});

// Budgets
Route::prefix('budgets')->name('budgets.')->group(function () {
    Route::get('/', [BudgetController::class, 'index'])->name('index');
    Route::post('/', [BudgetController::class, 'store'])->name('store');
    Route::delete('/{id}', [BudgetController::class, 'destroy'])->name('destroy');
});

// Analytics
Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');

// Settings (Placeholder for now)
Route::get('/settings', function () {
    return Inertia::render('Settings/Index');
})->name('settings');

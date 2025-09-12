<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/categories/income', [CategoryController::class, 'getPemasukan'])->name('categories.income');
Route::get('/categories/expense', [CategoryController::class, 'getPengeluaran'])->name('categories.expense');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Transaction routes
    Route::resource('transactions', TransactionController::class);
    
    // Report routes
    Route::get('/reports', [TransactionController::class, 'reports'])->name('reports.index');
Route::get('/reports/daily', [TransactionController::class, 'dailyReport'])->name('reports.daily');
Route::get('/reports/monthly', [TransactionController::class, 'monthlyReport'])->name('reports.monthly');
    
    // Category routes
    Route::resource('categories', CategoryController::class);
    Route::patch('/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    
});

require __DIR__.'/auth.php';

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
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/{id}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{id}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::patch('/transactions/{id}', [TransactionController::class, 'update'])->name('transactions.patch');
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    
    // Report routes
    Route::get('/reports', [TransactionController::class, 'reports'])->name('reports.index');
    Route::get('/reports/daily', [TransactionController::class, 'dailyReport'])->name('reports.daily');
    Route::get('/reports/monthly', [TransactionController::class, 'monthlyReport'])->name('reports.monthly');
    Route::get('/reports/pengeluaran-by-category', [TransactionController::class, 'getPengeluaranByCategory'])->name('reports.pengeluaran-by-category');
    
    // Category routes
    Route::resource('categories', CategoryController::class);
    Route::patch('/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    
});

require __DIR__.'/auth.php';

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\OrderController;

Route::get('/', HomeController::class)->name('home');

Route::middleware('auth')
    ->prefix('book')
    ->group(function () {
        Route::get('create', [BookController::class, 'create'])->name('books.create');
        Route::post('store', [BookController::class, 'store'])->name('books.store');
        Route::get('/{book:slug}/report/create', [BookReportController::class, 'create'])->name('books.report.create');
        Route::post('/{book}/report', [BookReportController::class, 'store'])->name('books.report.store');
        Route::get('/{book:slug}', [BookController::class, 'show'])->name('books.show');
    });

Route::middleware('auth')
    ->prefix('users')
    ->group(function () {
        Route::get('books', [BookController::class, 'index'])->name('user.books.list');
        Route::get('books/{book:slug}/edit', [BookController::class, 'edit'])->name('user.books.edit');
        Route::put('books/{book:slug}', [BookController::class, 'update'])->name('user.books.update');
        Route::delete('books/{book}', [BookController::class, 'destroy'])->name('user.books.destroy');

        Route::get('orders', [OrderController::class, 'index'])->name('user.orders.index');

        Route::get('settings', [UserSettingsController::class, 'index'])->name('user.settings');
        Route::post('settings/{user}', [UserSettingsController::class, 'update'])->name('user.settings.update');
        Route::post('settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('user.password.update');
    });

Route::middleware('can:admin')
    ->prefix('admin')
    ->namespace('Admin')
    ->group(function () {
        Route::get('/', AdminDashboardController::class)->name('admin.index');

        Route::resource('books', AdminBookController::class);

        Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('admin.books.approve');

        Route::resource('users', AdminUsersController::class);
    });

require __DIR__ . '/auth.php';

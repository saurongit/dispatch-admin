<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
Route::post('/orders/{id}/approve', [OrderController::class, 'approve'])->name('orders.approve');
Route::post('/orders/{id}/reject', [OrderController::class, 'reject'])->name('orders.reject');

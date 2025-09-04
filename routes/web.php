<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

Route::get('/', [UserController::class, 'home']);

Route::get('/shop', [ProductController::class, 'index']);

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');

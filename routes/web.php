<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

Route::get('/', [UserController::class, 'home']);

Route::get('/shop/{product}', [ProductController::class, 'show'])->name('products.show');

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');

Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

Route::post('/check-email', [UserController::class, 'checkEmail']);

Route::post('/signin', [UserController::class, 'signin']);

Route::post('/register', [UserController::class, 'register']);

Route::post('/verify-otp', [UserController::class, 'verifyOtp'])->name('verify-otp');

//SIGN IN ROUTES 
Route::post('/logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');
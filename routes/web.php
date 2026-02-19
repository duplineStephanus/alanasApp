<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use GuzzleHttp\Middleware;

Route::get('/', [UserController::class, 'home']);

Route::get('/shop/{product}', [ProductController::class, 'show'])->name('products.show');

Route::post('/check-email', [UserController::class, 'checkEmail']);

Route::post('/signin', [UserController::class, 'signin'])->middleware('guest');

Route::post('/register', [UserController::class, 'register'])->middleware('guest');

Route::post('/verify-otp', [UserController::class, 'verifyOtp'])->name('verify-otp');

//CART ROUTES 
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::delete('/cart/items/{item}', [CartController::class, 'remove'])->name('cart.items.remove');
Route::patch('/cart/items/{item}', [CartController::class, 'updateQuantity']);
Route::post('/cart/sync', [CartController::class, 'sync']);
Route::get('checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::get('/cart/empty', function () {
    return view('cart.empty');
})->name('cart.empty');

//SIGN IN ROUTES 
Route::post('/logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');
//or change middle ware later to valid email only 
Route::post('/resend-otp', [UserController::class, 'resendOtp'])->name('resend-otp');

Route::post('/prepare-otp', [UserController::class, 'prepareOtpForEmail'])->name('prepare-otp');
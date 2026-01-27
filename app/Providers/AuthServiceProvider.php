<?php

namespace App\Providers;

use App\Models\CartItem;
use App\Policies\CartItemPolicy;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
     protected $policies = [
        // other policies...
        CartItem::class => CartItemPolicy::class,
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

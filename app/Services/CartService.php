<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartService
{
    /**
     * Create a new class instance.
     */
    public function migrateCart(): void
    {
        if (!request()->hasCookie('cart_token')) {
            return;
        }

        $guestToken = request()->cookie('cart_token');
        $guestCart = Cart::where('guest_token', $guestToken)->first();

        if (!$guestCart) {
            return;
        }

        $user = auth()->user();
        $userCart = Cart::firstOrCreate(['user_id' => $user->id]);

        foreach ($guestCart->items as $guestItem) {
            $existing = CartItem::where('cart_id', $userCart->id)
                ->where('variant_id', $guestItem->variant_id)
                ->first();

            if ($existing) {
                $existing->quantity += $guestItem->quantity;
                $existing->save();
            } else {
                CartItem::create([
                    'cart_id'    => $userCart->id,
                    'product_id' => $guestItem->product_id,
                    'variant_id' => $guestItem->variant_id,
                    'quantity'   => $guestItem->quantity,
                    'price'      => $guestItem->price,
                ]);
            }
        }

        // Optional: clean up guest cart
        $guestCart->items()->delete();
        $guestCart->delete();

        // Optional: clear cookie
        // return response()->json([...])->withCookie(cookie()->forget('cart_token'));
    }
    public function removeItem(CartItem $item): void
    {
        $item->delete();

        $cart = $item->cart;

        if ($cart->items()->count() === 0) {
            $cart->delete();
        }
    }
}

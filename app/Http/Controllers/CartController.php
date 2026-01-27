<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddToCartRequest;

class CartController extends Controller
{
    protected function getCart()
    {
        $cart = null;

        if (Auth::check()) {
            $cart = Cart::with(['items.product', 'items.variant'])
                ->where('user_id', Auth::id())
                ->first();
        } else {
            $guestToken = request()->cookie('cart_token');
            if ($guestToken) {
                $cart = Cart::with(['items.product', 'items.variant'])
                    ->where('guest_token', $guestToken)
                    ->first();
            }
        }

        if (!$cart) {
            return [];
        }

        return $cart->items->map(function ($item) {
            return [
                'id'             => $item->id,
                'name'           => $item->product->name,
                'price'          => $item->price,
                'quantity'       => $item->quantity,
                'size'           => $item->variant?->size,
                'image'          => $item->variant?->image_url,
                'stock_quantity' => $item->variant?->stock_quantity,
            ];
        })->toArray();
    }
    public function index()
    {
        $cartItems = $this->getCart();

       //change to simply return response()->json cartItems 
        return response()->json([
            'items' => $cartItems
        ]);

    }

    public function add(AddToCartRequest $request)
    {
        if (auth()->check()) {
            $cart = Cart::firstOrCreate(
                ['user_id' => auth()->id()]
            );
        } else {
            $guestToken = request()->cookie('cart_token');
            $cart = Cart::firstOrCreate(
                ['guest_token' => $guestToken]
            );

          
        }
        // Find existing item in cart
        $item = CartItem::where('cart_id', $cart->id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($item) {
            $item->quantity += $request->quantity ?? 1;
            $item->save();
        } else {
            CartItem::create([
                'cart_id'    => $cart->id,
                'product_id' => $request->product_id,
                'variant_id' => $request->variant_id,
                'quantity'   => $request->quantity ?? 1,
                'price'      => \App\Models\ProductVariant::find($request->variant_id)->price
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart!',
            'cart'    => $cart->load('items')
        ]);
    }

    public function count()
    {
        $count = 0;

        if(auth()->check()){
            $cart = Cart::where('user_id', auth()->id())->first();
        }else{
            $guestToken = request()->cookie('cart_token');
            $cart = Cart::where('guest_token', $guestToken)->first();
        }
        if ($cart) {
            $count = $cart->items()->sum('quantity');
        }

        return response()->json(['count' => $count]);

    }

    // CartController.php
    public function checkout()
    {
        $items = $this->getCart();

        // Calculate subtotal, shipping, tax, total
        $subtotal = collect($items)->sum(fn($item) => $item['price'] * $item['quantity']);
        $shipping = 10.00; // fixed
        $tax = $subtotal * 0.06; // e.g., 6% tax
        $total = $subtotal + $shipping + $tax;

        return view('cart.checkout', compact('items', 'subtotal', 'shipping', 'tax', 'total'));
    }

    public function remove(CartItem $item, CartService $cartService)
    {
        $this->authorize('delete', $item);

        $cartService->removeItem($item);

        return response()->json(['success' => true]);
    }

    public function updateQuantity(Request $request, CartItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $item->update([
            'quantity' => $request->quantity
        ]);

        return response()->json(['success' => true]);
    }

    public function sync(Request $request)
    {

        
        foreach ($request->items as $item) {
            CartItem::where('id', $item['id'])
                ->update([
                    'quantity' => $item['quantity']
                ]);
        }

        return response()->json(['success' => true]);
    }
}

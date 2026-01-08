<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddToCartRequest;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        // Guest user
        if (!Auth::check()) {
            $cart = Cart::where('session_id', session()->getId())
                ->with('items.product')
                ->first();

            $cartItems = [];

            if ($cart) {
                $cartItems = $cart->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->product->name,
                        'price' => $item->product->price,
                        'quantity' => $item->quantity,
                        'size' => $item->size,
                        'variant' => $item->variant,
                        'image' => $item->product->image_path,
                    ];
                })->toArray();
            }

            return view('cart.index', compact('cartItems'));
        }

        // Logged-in user
        $cart = Cart::where('user_id', Auth::id())
            ->with('items.product')
            ->first();

        $cartItems = [];

        if ($cart) {
            $cartItems = $cart->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'size' => $item->size,
                    'variant' => $item->variant,
                    'image' => $item->product->image_path,
                ];
            })->toArray();
        }

        return view('cart.index', compact('cartItems'));
    }

    public function add(AddToCartRequest $request)
    {
        // Get session ID for guest user
        $sessionId = session()->getId();

        // Find or create cart
        $cart = Cart::firstOrCreate(
            ['session_id' => $sessionId],
            ['user_id' => auth()->id()]
        );

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
            $cart = Cart::where('session_id', session()->getId())->first();
        }
        if ($cart) {
            $count = $cart->items()->sum('quantity');
        }

        return response()->json(['count' => $count]);

    }
    
}

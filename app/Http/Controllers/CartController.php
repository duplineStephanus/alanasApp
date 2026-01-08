<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddToCartRequest;

class CartController extends Controller
{
    public function index(){

        // Guest user
        if (!Auth::check()) {
            $cartItems = session()->get('cart', []);
            return view('cart.index', compact('cartItems'));
        }

        // Logged-in user
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product')
            ->get()
            ->map(function ($cart) {
                return [
                    'id' => $cart->id,
                    'name' => $cart->product->name,
                    'price' => $cart->product->price,
                    'quantity' => $cart->quantity,
                    'size' => $cart->size,
                    'variant' => $cart->variant,
                    'image' => $cart->product->image_path,
                ];
            })
            ->toArray();

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
        // Get session ID for guest user
        $sessionId = session()->getId();

        // Optional: log session ID for testing
        logger("Session ID: {$sessionId}");

        // Find cart for current user/session
        $cart = Cart::where('session_id', $sessionId)
            ->orWhere('user_id', auth()->id())
            ->first();

        // Optional: log cart for testing
        logger("Cart: " . json_encode($cart));

        $count = 0;
        
        if ($cart) {
            $count = $cart->items()->sum('quantity');
        }

        return response()->json([
            'count' => $count
        ]);
    }
    
}

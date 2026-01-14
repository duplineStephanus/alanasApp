<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddToCartRequest;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    protected function getCart () {
        $cartItems = [];

        // Guest user
        if (!Auth::check()) {
            $cart = Cart::where('session_id', session()->getId())
                ->with('items.product', 'items.variant')
                ->first();

            if ($cart) {
                $cartItems = $cart->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->product->name,
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'size' => $item->variant->size,
                        'image' => $item->variant->image_url,
                        'stock_quantity' => $item->variant->stock_quantity,
                    ];
                })->toArray();
            }
        }

        // Logged-in user
        $cart = Cart::where('user_id', Auth::id())
            ->with('items.product')
            ->first();

            logger('cart id: ' . $cart->id);

        if ($cart) {
            $cartItems = $cart->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->product->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'size' => $item->variant->size,
                    'image' => $item->variant->image_url,
                    'stock_quantity' => $item->variant->stock_quantity,
                ];
            })->toArray();
        }

        return $cartItems;
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
        $user= auth()->user();

        if ($user) {
            // Logged-in: use user_id
            $cart = Cart::firstOrCreate(
                ['user_id' => $user->id],
                ['session_id' => session()->getId()]
            );
        } else {
            // Guest: use session_id
            $cart = Cart::firstOrCreate(
                ['session_id' => session()->getId()]
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
            $cart = Cart::where('session_id', session()->getId())->first();
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

    public function remove(CartItem $item)
    {
        // Ensure item belongs to the current cart
        if (auth()->check()) {
            $cart = Cart::where('user_id', auth()->id())->first();
        } else {
            $cart = Cart::where('session_id', session()->getId())->first();
        }

        if (! $cart || $item->cart_id !== $cart->id) {
            abort(403);
        }

        $item->delete();

        return response()->json([
            'success' => true
        ]);
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

        logger('syncing cart');
        
        foreach ($request->items as $item) {
            CartItem::where('id', $item['id'])
                ->update([
                    'quantity' => $item['quantity']
                ]);
        }

        return response()->json(['success' => true]);
    }
}

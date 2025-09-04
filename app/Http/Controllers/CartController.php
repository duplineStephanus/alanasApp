<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add (Request $request) {
        $productId = $request->product_id; 
        $variantId = $request->variant_id; 

        //simple cart stored in session 
        $cart = session()->get('cart', []);

        $key = $productId . '-' . $variantId; 

        if(isset($cart[$key])){
            $cart[$key]['quantity'] += 1; 
        } else {
            $cart[$key] = [
                'product_id' => $productId, 
                'variant-id' => $variantId, 
                'quantity' => 1, 
            ];
        }

        session()->put('cart', $cart);

        return response()->json(['cart' => $cart, 'message' => 'Added to cart!']);

    }
}

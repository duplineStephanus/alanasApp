<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index (){

        $products = Product::with('variants')->get();

        return view('shop.index', compact('products'));

    }

    public function show (Product $product)
    {
        $product->load('variants');
        return view('shop.show', compact('product'));
    }
}

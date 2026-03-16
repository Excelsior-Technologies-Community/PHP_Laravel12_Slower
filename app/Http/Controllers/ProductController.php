<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{

    public function index()
    {

        // intentionally slow query
        $products = Product::where('price', '>', 100)->get();

        return view('products', compact('products'));

    }

}
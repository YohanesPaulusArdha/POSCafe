<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class POSController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        $categories = Category::all();

       
        return view('POS', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
}
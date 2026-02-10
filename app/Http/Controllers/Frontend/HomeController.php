<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featured_products = Product::active()
            ->featured()
            ->inStock()
            ->take(8)
            ->get();

        $new_arrivals = Product::active()
            ->inStock()
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::active()
            ->parent()
            ->orderBy('order')
            ->take(6)
            ->get();

        return view('frontend.home', compact('featured_products', 'new_arrivals', 'categories'));
    }
}

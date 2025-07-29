<?php

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\Product;

class MainController extends Controller
{
    public function main()
    {
        $page = request('page', 1);
        $products = Cache::remember("products.page.$page", 60, function () {
            return Product::with('category')->orderBy('id')->paginate(20);
        });
        Cache::flush();
        return view('main', compact('products'));
    }
}
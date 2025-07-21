<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

class MainController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if(!$user){
            return response()->json(['message' => 'Kullanıcı bulunamadı.'], 404);
        }

        $page = request('page', 1);
        $products = Cache::remember("products.page.$page", 60, function () {
            return Product::with('category')->orderBy('id')->paginate(20);
        });
        return response()->json($products);
    }
}
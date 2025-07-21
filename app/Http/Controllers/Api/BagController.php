<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bag;
use App\Models\Product;
use App\Models\Category;
use App\Models\BagItem;
use Illuminate\Support\Facades\Cache;

class BagController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $bag = Bag::where('Bag_User_id', $user->id)->first();
        if(!$bag){
            return response()->json(['error' => 'Sepet bulunamadı!'], 404);
        }
        $products = $bag->bagItems()->with('product.category')->get();
        return response()->json($products);
    }
    public function store(Request $request)
    {
        $user = auth()->user(); 
        $bag = Bag::firstOrCreate(['Bag_User_id' => $user->id]);
        $productItem = $bag->bagItems()->where('product_id', $request->product_id)->first();
        $product = Product::find($request->product_id);
        
        if ($product->stock_quantity == 0) {
            return response()->json(['error' => 'Ürün stokta yok!'], 400);

        } else if ($productItem) {
            $productItem->quantity += 1;
            $productItem->save();
            
        } else {
            $bag->bagItems()->create([
                'product_id' => $request->product_id,
                'quantity' => 1
            ]);
            
        }
        Cache::flush();
        return response()->json(['message' => 'Ürün sepete eklendi.']);
    }

    public function show(Request $request)
    {
        $user = auth()->user();
        $bag = Bag::where('Bag_User_id', $user->id)->first();
        if(!$bag){
            return response()->json(['error' => 'Sepet bulunamadı!'], 404);
        }
        $bagItem = $bag->bagItems()->where('product_id', $request->product_id)
                        ->where('bag_id', $bag->id)
                        ->first();
        if(!$bagItem){
            return response()->json(['error' => 'Ürün bulunamadı!'], 404);
        }
        return response()->json($bagItem);
    }
    public function destroy(Request $request)
    {
        $user = auth()->user();
        $bag = Bag::where('Bag_User_id', $user->id)->first();
        if(!$bag){
            return response()->json(['error' => 'Sepet bulunamadı!'], 404);
        }
        $bagItem = $bag->bagItems()->where('product_id', $request->product_id)
                        ->where('bag_id', $bag->id)
                        ->first();

        if(!$bagItem){
            return response()->json(['error' => 'Ürün bulunamadı!'], 404);
        }
        if ($bagItem) {
            $product = Product::find($bagItem->product_id);

            if ($bagItem->quantity > 1) {
                $bagItem->quantity -= 1;
                $bagItem->save();
                $message = 'Ürün sepetten 1 adet silindi.';
            } else {
                $bagItem->delete();
                $message = 'Ürün sepetten tamamen silindi.';
            }
        }
        Cache::flush();  
        return response()->json(['message' => $message]);
    }
}

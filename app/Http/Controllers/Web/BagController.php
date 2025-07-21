<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bag;
use App\Models\Product;
use App\Models\Category;
use App\Models\BagItem;
use Illuminate\Support\Facades\Cache;


class BagController extends Controller
{
    public function bag(Request $request)
    {
        $user = auth()->user();
        $bag = Bag::where('Bag_User_id', $user->id)->first();
        $products = $bag ? $bag->bagItems()->with('product.category')->get() : collect();
        return view('bag', compact('products'));
    }
    public function add(Request $request)
    {
        
        $user = auth()->user(); 
        $bag = Bag::firstOrCreate(['Bag_User_id' => $user->id]);
        $productItem = $bag->bagItems()->where('product_id', $request->product_id)->first();
        $product = Product::find($request->product_id);
        
        if ($product->stock_quantity == 0) {
            return redirect('main')->with('error', 'Ürün stokta yok!');

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
        return redirect()->route('main')->with('success', 'Ürün sepete eklendi!');
    }
    public function delete($id)
    {   
        $bagItem = BagItem::find($id);

        if ($bagItem) {
            $product = Product::find($bagItem->product_id);

            if ($bagItem->quantity > 1) {
                $bagItem->quantity -= 1;
                $bagItem->save();
            } else {
                $bagItem->delete();
            }
        }

        Cache::flush(); 
        return redirect()->route('bag')->with('success', 'Ürün sepetten 1 adet silindi!');
    }
}
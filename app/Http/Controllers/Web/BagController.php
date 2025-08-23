<?php

namespace App\Http\Controllers\Web;

use App\Services\Bag\Contracts\BagInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class BagController extends Controller
{

    protected $bagService;
    public function __construct(BagInterface $bagService)
    {
        $this->bagService = $bagService;
    }

    public function bag(Request $request)
    {
        $bag = $this->bagService->getBag();
        return view('bag', $bag);
    }

    public function add(Request $request)
    {
        $productItem = $this->bagService->addToBag($request->product_id);

        if (is_array($productItem) && isset($productItem['error'])) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $productItem['error']]);
            }
            return redirect()->route('main')->with('error', $productItem['error']);
        }
        
        if(!$productItem){
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Ürün bulunamadı!']);
            }
            return redirect()->route('main')->with('error', 'Ürün bulunamadı!');
        }
        
        Cache::flush();
        
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Ürün sepete eklendi.']);
        }
        
        return redirect()->back()->with('success', 'Ürün sepete eklendi.');
    }

    public function update(Request $request, $id)
    {
        $quantity = $request->input('quantity');
        
        if($quantity < 1){
            return redirect()->route('bag')->with('error', 'Ürün adedi 1\'den az olamaz!');
        }

        $bagItem = $this->bagService->updateBagItem($id, $quantity);

        if(isset($bagItem['error'])){
            return redirect()->route('bag')->with('error', $bagItem['error']);
        }

        Cache::flush();

        return redirect()->route('bag')->with('success', 'Ürün adedi güncellendi.');
    }

    public function delete($id)
    {   
        $result = $this->bagService->destroyBagItem($id);

        if(isset($result['success']) && !$result['success']){
            return redirect()->route('bag')->with('error', $result['message']);
        }

        return redirect()->route('bag')->with('success', $result['message'] ?? 'Ürün sepetten silindi.');
    }

}
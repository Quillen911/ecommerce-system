<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\UserBagTrait;
use App\Helpers\ResponseHelper;
use App\Services\BagService;

class BagController extends Controller
{
    use UserBagTrait;
    protected $bagService;

    public function __construct(BagService $bagService)
    {
        $this->bagService = $bagService;
    }

    public function bag(Request $request)
    {
        $user = $this->getUser();
        $bag = $this->getUserBag();

        if(!$bag){
            return redirect()->route('main')->with('error', 'Sepetiniz bulunamadı!');
        }

        $products = $this->bagService->getIndexBag($bag);

        if($products->isEmpty()){
            return redirect()->route('bag')->with('success', 'Sepetiniz boş!');
        }

        return view('bag', compact('products'));
    }

    public function add(Request $request)
    {
        $user = $this->getUser();
        $bag = $this->getUserBag();

        if(!$bag){
            return redirect()->route('main')->with('error', 'Sepetiniz bulunamadı!');
        }

        $result = $this->bagService->getAddBag($bag, $request->product_id);

        if(!$result){
            return redirect()->route('main')->with('error', 'Ürün bulunamadı!');
        }

        return redirect()->route('main')->with('success', 'Ürün sepete eklendi.');
    }

    public function delete(Request $request)
    {
        $user = $this->getUser();
        $bag = $this->getUserBag();

        if(!$bag){
            return redirect()->route('bag')->with('error', 'Sepet bulunamadı!');
        }

        $result = $this->bagService->destroyBagItem($bag, $request->product_id);

        if(!$result['success']){
            return redirect()->route('bag')->with('error', $result['message']);
        }

        return redirect()->route('bag')->with('success', $result['message']);
    }
}
<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bag;
use App\Models\Product;
use App\Models\Category;
use App\Models\BagItem;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\BaseApiRequest;
use App\Traits\UserBagTrait;
use App\Helpers\ResponseHelper;
use App\Services\BagService;


class BagController extends Controller
{   
    use UserBagTrait;
    protected $bagService;

    public function __construct(BagService $bagService){

        $this->bagService = $bagService;
    }

    public function bag(Request $request)
    {   
        $user = $this->getUser();
        $bag = $this->getUserBag();

        if(!$bag){
            return redirect()->route('main')->with('error', 'Sepetiniz bulunamadı!');
        }
        $products = $bag->bagItems()->with('product.category')->get();
        if(!$products){
            return redirect()->route('bag')->with('error', 'Sepetiniz boş!');
        }
        return view('bag', compact('products'));
    }
    
    public function add(Request $request)
    {
        
        $user = $this->getUser(); 
        $result = $this->bagService->getAddBag($user->id, $request->product_id);
        if(!$result['success']){
            return redirect()->route('main')->with('error', $result['message']);
        }
        return redirect()->route('main')->with('success', $result['message']);
    }
    public function delete(Request $request)
    {   
        $user = $this->getUser();
        $bag = $this->getUserBag();
    
        if(!$bag){
            return redirect()->route('bag')->with('error', 'Sepet Bulunamadı');
        }

        $result = $this->bagService->destroyBagItem($request->product_id, $user->id, $bag->id);
        
        if(!$result['success']){
            return redirect()->route('bag')->with('error', $result['message']);
        }
        
        return redirect()->route('bag')->with('success', $result['message']);
    }
}
<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\UserBagTrait;
use App\Helpers\ResponseHelper;
use App\Services\BagService;
use App\Models\Bag;
use App\Models\BagItem;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use App\Services\Campaigns\CampaignManager\CampaignManager;
use App\Models\Campaign;

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
        $bag = $this->bagService->getIndexBag();
        
        return view('bag', $bag);
    }

    public function add(Request $request)
    {
        $user = $this->getUser();
        $bag = Bag::firstOrCreate(['bag_user_id' => $user->id]);

        if(!$bag){
            return redirect()->route('main')->with('error', 'Sepetiniz bulunamadı!');
        }

        $productItem = $this->bagService->getAddBag($bag, $request->product_id);

        if (is_array($productItem) && isset($productItem['error'])) {
            return redirect()->route('main')->with('error', $productItem['error']);
        }
        
        if(!$productItem){
            return redirect()->route('main')->with('error', 'Ürün bulunamadı!');
        }
        
        Cache::flush();
        return redirect()->route('main')->with('success', 'Ürün sepete eklendi.');
    }

    public function delete($id)
    {   
        $user = $this->getUser();
        $bag = $this->getUserBag();

        if(!$bag){
            return redirect()->route('bag')->with('error', 'Sepet bulunamadı!');
        }
        
        $result = $this->bagService->destroyBagItem($bag, $id);

        if(isset($result['success']) && !$result['success']){
            return redirect()->route('bag')->with('error', $result['message']);
        }

        return redirect()->route('bag')->with('success', $result['message'] ?? 'Ürün sepetten silindi.');
    }
}
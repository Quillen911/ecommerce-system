<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Contracts\Store\StoreRepositoryInterface;

class SellerController extends Controller
{
    protected $storeRepository;
    public function __construct(StoreRepositoryInterface $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }
    public function seller()
    {
        $seller = auth('seller_web')->user();
        $sellerInfo = $this->storeRepository->getStoreBySellerId($seller->id);
        return view('Seller.seller', compact('sellerInfo'));
    }

}

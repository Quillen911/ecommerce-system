<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Contracts\Store\StoreRepositoryInterface;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;

class SellerController extends Controller
{
    protected $storeRepository;
    protected $authenticationRepository;
    public function __construct(StoreRepositoryInterface $storeRepository, AuthenticationRepositoryInterface $authenticationRepository)
    {
        $this->storeRepository = $storeRepository;
        $this->authenticationRepository = $authenticationRepository;
    }
    public function seller()
    {
        $seller = $this->authenticationRepository->getSeller();
        $sellerInfo = $this->storeRepository->getStoreBySellerId($seller->id);
        return view('Seller.seller', compact('sellerInfo'));
    }

}

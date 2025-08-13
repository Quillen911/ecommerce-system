<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Seller;
class SellerController extends Controller
{
    public function seller()
    {
        $seller = auth('seller')->user();
        $sellerInfo = Store::where('seller_id', $seller->id)->first();
        return view('Seller.seller', compact('sellerInfo'));
    }

}

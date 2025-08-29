<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SellerSettingsController extends Controller
{
    public function index()
    {
        $sellerInfo = auth('seller')->user();
        return view('Seller.settings', compact('sellerInfo'));
    }

    public function store(Request $request)
    {
        $sellerInfo = auth('seller')->user();
        $sellerInfo->update($request->all());
        $sellerInfo->save();
        return redirect()->route('seller.settings');
    }
}
<?php

namespace App\Http\Controllers\Web\Payments;

use App\Http\Controllers\Controller;
use App\Services\Payments\CreditCardService;
use App\Http\Requests\Payments\CreditCardStoreRequest;

class CreditCardController extends Controller
{
    protected $creditCardService;

    public function __construct(CreditCardService $creditCardService)
    {
        $this->creditCardService = $creditCardService;
    }

    public function storeCreditCard(CreditCardStoreRequest $request)
    {
        $user = auth()->user();
        $creditCard = $this->creditCardService->storeCreditCard($request, $user);
        
        if ($creditCard) {
            return redirect()->back()->with('success', 'Kredi kartı başarıyla eklendi!');
        } else {
            return redirect()->back()->with('error', 'Kredi kartı eklenirken hata oluştu!');
        }
    }
}
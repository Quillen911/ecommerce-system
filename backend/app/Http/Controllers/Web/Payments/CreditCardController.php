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
        $result = $this->creditCardService->storeCreditCard($request);
        
        // Service JsonResponse döndürüyor, data'sını alalım
        if ($result instanceof \Illuminate\Http\JsonResponse) {
            $data = $result->getData(true);
            
            if ($data['success'] ?? false) {
                return redirect()->back()->with('success', 'Kredi kartı başarıyla eklendi!');
            } else {
                return redirect()->back()->with('error', $data['message'] ?? 'Kredi kartı eklenirken hata oluştu!');
            }
        }
        
        // Fallback
        return redirect()->back()->with('error', 'Kredi kartı eklenirken hata oluştu!');
    }
}
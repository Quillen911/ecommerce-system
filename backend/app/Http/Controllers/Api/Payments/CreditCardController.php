<?php

namespace App\Http\Controllers\Api\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Services\Payments\CreditCardService;
use App\Http\Requests\Payments\CreditCardStoreRequest;
use App\Http\Requests\Payments\CreditCardUpdateRequest;

class CreditCardController extends Controller
{

    protected $creditCardService;

    public function __construct(CreditCardService $creditCardService)
    {
        $this->creditCardService = $creditCardService;
    }

    public function index()
    {
        return $this->creditCardService->indexCreditCard();
    }

    public function store(CreditCardStoreRequest $request)
    {
        return $this->creditCardService->storeCreditCard($request);
    }

    public function show($id){
        return $this->creditCardService->showCreditCard($id);
    }

    public function update(CreditCardUpdateRequest $request, $id){

        return $this->creditCardService->updateCreditCard($request, $id);
    }

    public function destroy($id){
        return $this->creditCardService->destroyCreditCard($id);
    }
} 
<?php

namespace App\Services\Payments;

use App\Models\CreditCard;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Payments\CreditCardStoreRequest;
use App\Http\Requests\Payments\CreditCardUpdateRequest;

class CreditCardService
{
    public function indexCreditCard()
    {
        $user = auth()->user();
        $creditCard = CreditCard::where('user_id', $user->id)->orderBy('id')->get();
        return $creditCard;
    }

    public function storeCreditCard(CreditCardStoreRequest $request, $user)
    {
        $creditCard = CreditCard::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'card_number' => $request->card_number,
            'cvv' => $request->cvv,
            'expire_year' => $request->expire_year,
            'expire_month' => $request->expire_month,
            'card_type' => $request->card_type,
            'card_holder_name' => $request->card_holder_name,
            'is_active' => $request->is_active,
        ]);
        return $creditCard;
    }
    public function showCreditCard($id)
    {
        $creditCard = CreditCard::find($id);
        if(!$creditCard){
            return null;
        }
        return $creditCard;
    }
    public function updateCreditCard(CreditCardUpdateRequest $request, $id)
    {
        $creditCard = CreditCard::find($id);
        if(!$creditCard){
            return null;
        }
        $creditCard->update($request->all());
        return $creditCard;
    }
    public function destroyCreditCard($id)
    {
        $creditCard = CreditCard::findOrFail($id);
        if(!$creditCard){
            return null;
        }
        $creditCard->delete();
        return $creditCard;
    }
}
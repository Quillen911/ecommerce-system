<?php

namespace App\Services\Payments;

use App\Http\Requests\Payments\CreditCardStoreRequest;
use App\Http\Requests\Payments\CreditCardUpdateRequest;
use App\Repositories\Contracts\CreditCard\CreditCardRepositoryInterface;
use App\Helpers\ResponseHelper;

class CreditCardService
{
    protected $creditCardRepository;
    public function __construct(CreditCardRepositoryInterface $creditCardRepository)
    {
        $this->creditCardRepository = $creditCardRepository;
    }
    public function indexCreditCard()
    {
        $user = auth()->user();
        if(!$user){
            return ResponseHelper::error('Kullanıcı bulunamadı');
        }
        $creditCard = $this->creditCardRepository->getCreditCardsByUserId($user->id);
        if(!$creditCard){
            return ResponseHelper::notFound('Kredi kartı bulunamadı');
        }
        return ResponseHelper::success('Kredi kartı listelendi',$creditCard);
    }

    public function storeCreditCard(CreditCardStoreRequest $request)
    {
        $user = auth()->user();
        if(!$user){
            return ResponseHelper::error('Kullanıcı bulunamadı');
        }
        $data = $request->all();
        $data['user_id'] = $user->id;
        $creditCard = $this->creditCardRepository->createCreditCard($data);
        if(!$creditCard){
            return ResponseHelper::error('Kredi kartı oluşturulamadı');
        }
        return ResponseHelper::success('Kredi kartı başarıyla oluşturuldu',$creditCard);
    }
    public function showCreditCard($id)
    {
        
        $user = auth()->user();
        if(!$user){
            return ResponseHelper::error('Kullanıcı bulunamadı');
        }
        $creditCard = $this->creditCardRepository->getCreditCardByUserId($user->id,$id);
        if(!$creditCard){
            return ResponseHelper::notFound('Kredi kartı bulunamadı');
        }
        return ResponseHelper::success('Kredi kartı detayı',$creditCard);
        
        
    }
    public function updateCreditCard(CreditCardUpdateRequest $request, $id)
    {
        try{
            $user = auth()->user();
            if(!$user){
                return ResponseHelper::error('Kullanıcı bulunamadı');
            }
            $data = $request->all();
            $creditCard = $this->creditCardRepository->updateCreditCard($data, $user->id, $id);
            
            if(!$creditCard){
                return ResponseHelper::error('Kredi kartı güncellenemedi');
            }
            return ResponseHelper::success('Kredi kartı başarıyla güncellendi', $creditCard);   
        }
        catch(\Exception $e){
            return ResponseHelper::error('Kredi kartı güncellenemedi');
        }
    }
    public function destroyCreditCard($id)
    {
        try{
            $user = auth()->user();
            if(!$user){
                return ResponseHelper::error('Kullanıcı bulunamadı');
            }
            $creditCard = $this->creditCardRepository->getCreditCardByUserId($user->id,$id);
            if(!$creditCard || $creditCard->user_id !== $user->id){
                return ResponseHelper::notFound('Kredi kartı bulunamadı');
            }
            $creditCard = $this->creditCardRepository->deleteCreditCard($user->id, $id);
            if(!$creditCard){
                return ResponseHelper::error('Kredi kartı silinemedi');
            }
            return ResponseHelper::success('Kredi kartı başarıyla silindi',$creditCard);
        }
        catch(\Exception $e){
            return ResponseHelper::error('Kredi kartı silinemedi');
        }
    }
}
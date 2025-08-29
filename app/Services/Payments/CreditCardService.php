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
        
        try {
            // İyzico CardManagement API ile gerçek token oluştur
            $iyzicoService = new IyzicoPaymentService();
            $tokenData = [
                'card_holder_name' => $request->card_holder_name,
                'card_number' => $request->card_number,
                'expire_month' => $request->expire_month,
                'expire_year' => $request->expire_year,
                'card_alias' => $request->name,
                'email' => $user->email ?? 'test@test.com'
            ];
            
            \Log::info('İyzico token oluşturma başlıyor', ['user_id' => $user->id, 'card_alias' => $request->name]);
            
            $tokenResult = $iyzicoService->createCardToken($tokenData, $user->id);
            
            \Log::info('İyzico token sonucu', $tokenResult);
            
            if (!$tokenResult['success']) {
                \Log::error('İyzico token oluşturma hatası', $tokenResult);
                return ResponseHelper::error('Kart kaydedilemedi: ' . $tokenResult['error']);
            }
            
            $data = [
                'user_id' => $user->id,
                'name' => $request->name,
                'last_four_digits' => substr($request->card_number, -4), 
                'expire_year' => $request->expire_year,
                'expire_month' => $request->expire_month,
                'card_type' => $request->card_type,
                'card_holder_name' => $request->card_holder_name,
                'is_active' => true,
                'iyzico_card_token' => $tokenResult['card_token'],
                'iyzico_card_user_key' => $tokenResult['card_user_key']
            ];
            
            $creditCard = $this->creditCardRepository->createCreditCard($data);
            if(!$creditCard){
                return ResponseHelper::error('Kredi kartı oluşturulamadı');
            }
            return ResponseHelper::success('Kredi kartı başarıyla oluşturuldu',$creditCard);
            
        } catch (\Exception $e) {
            return ResponseHelper::error('Kart kaydedilemedi: ' . $e->getMessage());
        }
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
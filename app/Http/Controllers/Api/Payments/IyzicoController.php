<?php

namespace App\Http\Controllers\Api\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Services\Payments\IyzicoService;
use App\Http\Requests\Payments\CreditCardStoreRequest;
use App\Http\Requests\Payments\CreditCardUpdateRequest;

class IyzicoController extends Controller
{

    protected $iyzicoService;

    public function __construct(IyzicoService $iyzicoService)
    {
        $this->iyzicoService = $iyzicoService;
    }

    public function index()
    {
        $paymentInfo = $this->iyzicoService->indexCreditCard();
        if(!$paymentInfo){
            return ResponseHelper::notFound('Ödeme bilgileri bulunamadı');
        }
        return ResponseHelper::success('Ödeme bilgileri listelendi',$paymentInfo);
    }

    public function store(CreditCardStoreRequest $request){
        try{
            $user = auth()->user();
            if(!$user){
                return ResponseHelper::error('Kullanıcı bulunamadı');
            }
            else{
            $paymentInfo = $this->iyzicoService->storeCreditCard($request, $user);
            if(!$paymentInfo){
                return ResponseHelper::error('Ödeme bilgileri oluşturulamadı');
            }
            return ResponseHelper::success('Ödeme bilgileri başarıyla oluşturuldu',$paymentInfo);
            }
        }
        catch(\Exception $e){
            return ResponseHelper::error('Ödeme bilgileri oluşturulamadı');
        }
    }

    public function show($id){
        $paymentInfo = $this->iyzicoService->showCreditCard($id);
        if(!$paymentInfo){
            return ResponseHelper::notFound('Ödeme bilgileri bulunamadı');
        }
        return ResponseHelper::success('Ödeme bilgileri detayı',$paymentInfo);
    }

    public function update(CreditCardUpdateRequest $request, $id){
        try{
            $paymentInfo = $this->iyzicoService->updateCreditCard($request, $id);
            if(!$paymentInfo){
                return ResponseHelper::error('Ödeme bilgileri güncellenemedi');
            }
            return ResponseHelper::success('Ödeme bilgileri başarıyla güncellendi',$paymentInfo);
        }
        catch(\Exception $e){
            return ResponseHelper::error('Ödeme bilgileri güncellenemedi');
        }
    }

    public function destroy($id){
        $paymentInfo = $this->iyzicoService->destroyCreditCard($id);
        if(!$paymentInfo){
            return ResponseHelper::error('Ödeme bilgileri silinemedi');
        }
        return ResponseHelper::success('Ödeme bilgileri başarıyla silindi',$paymentInfo);
    }
} 
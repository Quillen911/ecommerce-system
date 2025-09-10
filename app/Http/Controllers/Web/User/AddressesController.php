<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\User\AddressesService;
use App\Http\Requests\User\AddressesStoreRequest;
use App\Http\Requests\User\AddressesUpdateRequest;

class AddressesController extends Controller
{
    protected $addressesService;
    public function __construct(AddressesService $addressesService)
    {
        $this->addressesService = $addressesService;
    }
    public function store(AddressesStoreRequest $request)
    {
        $adress = $this->addressesService->storeAddresses($request->validated());
        return redirect()->route('order')->with('success', 'Adres başarıyla oluşturuldu');
    }
    public function update(AddressesUpdateRequest $request, $id)
    {
        try {
            $address = $this->addressesService->updateAddresses($request->validated(), $id);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Adres başarıyla güncellendi'
                ]);
            }
            
            return redirect()->route('order')->with('success', 'Adres başarıyla güncellendi');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Adres güncellenirken bir hata oluştu',
                    'errors' => $e->getMessage()
                ], 422);
            }
            
            return redirect()->route('order')->with('error', 'Adres güncellenirken bir hata oluştu');
        }
    }
}

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
    public function addresses()
    {
        $addresses = $this->addressesService->indexAddresses();
        return view('user.userAddresses', ['addresses' => $addresses]);
    }
    public function store(AddressesStoreRequest $request)
    {
        $adress = $this->addressesService->storeAddresses($request->validated());
        if(route('user.addresses')){
            return redirect()->route('user.addresses')->with('success', 'Adres başarıyla oluşturuldu');
        }
        return redirect()->route('order')->with('success', 'Adres başarıyla oluşturuldu');
    }
    public function update(AddressesUpdateRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
            
            // Boş string'leri null'a çevir
            $nullableFields = ['address_line_2', 'postal_code', 'notes'];
            foreach ($nullableFields as $field) {
                if (isset($validatedData[$field]) && $validatedData[$field] === '') {
                    $validatedData[$field] = null;
                }
            }
            
            $address = $this->addressesService->updateAddresses($validatedData, $id);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Adres başarıyla güncellendi'
                ]);
            }
            
            if(route('user.addresses')){
                return redirect()->route('user.addresses')->with('success', 'Adres başarıyla güncellendi');
            }
            return redirect()->route('order')->with('success', 'Adres başarıyla güncellendi');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Adres güncellenirken bir hata oluştu',
                    'errors' => ['general' => $e->getMessage()]
                ], 422);
            }
            
            if(route('user.addresses')){
                return redirect()->route('user.addresses')->with('error', 'Adres güncellenirken bir hata oluştu');
            }
            return redirect()->route('order')->with('error', 'Adres güncellenirken bir hata oluştu');
        }
    }

    public function destroy($id)
    {
        $address = $this->addressesService->destroyAddresses($id);
        return redirect()->route('user.addresses')->with('success', 'Adres başarıyla silindi');
    }
    
}

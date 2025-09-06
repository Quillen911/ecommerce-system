<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;

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

    public function index()
    {
        return $this->addressesService->indexAddresses();
    }

    public function store(AddressesStoreRequest $request)
    {
        return $this->addressesService->storeAddresses($request->validated());
    }

    public function show($id)
    {
        return $this->addressesService->showAddresses($id);
    }
    
    public function update(AddressesUpdateRequest $request, $id)
    {
        return $this->addressesService->updateAddresses($request->validated(), $id);
    }

    public function destroy($id)
    {
        return $this->addressesService->destroyAddresses($id);
    }
}
